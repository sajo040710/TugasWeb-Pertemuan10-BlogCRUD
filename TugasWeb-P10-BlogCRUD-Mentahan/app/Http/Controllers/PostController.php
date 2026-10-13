<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

/**
 * PostController — berisi 7 method CRUD + 3 method tempat sampah.
 *
 * Pola umum setiap method:
 *   1. Terima permintaan dari route.
 *   2. Ambil / ubah data lewat model Post.
 *   3. Kirim hasilnya ke view, atau alihkan (redirect) ke halaman lain.
 *
 * Catatan "Post $post" pada parameter:
 *   Laravel membaca id dari URL, mencari post-nya di database, lalu
 *   mengisikannya ke $post. Jika tidak ketemu, otomatis tampil 404.
 */
class PostController extends Controller
{
    /**
     * READ (daftar)
     *   1. Ambil kata kunci dari ?q=
     *   2. Ambil post yang cocok dengan kata kunci (semua post jika kosong)
     *   3. Urutkan dari yang terbaru
     *   4. Bagi per 6 post per halaman
     *   5. Kirim ke view index
     */
    public function index(Request $request): View
    {
        $posts = Post::query()
            ->search($request->query('q'))
            ->latest()
            ->paginate(6)
            ->withQueryString(); // supaya ?q= tidak hilang saat pindah halaman

        return view('posts.index', compact('posts'));
    }

    /** Tampilkan form kosong untuk menulis post baru. */
    public function create(): View
    {
        return view('posts.create');
    }

    /**
     * CREATE (simpan post baru)
     *   1. Data sudah dicek oleh PostRequest. Jika salah, otomatis kembali ke form.
     *   2. Ambil data teks (judul, isi).
     *   3. JIKA ada file gambar -> simpan file, catat lokasinya.
     *   4. Simpan post ke database.
     *   5. JIKA terjadi error -> kembali ke form dengan pesan gagal.
     *   6. JIKA berhasil -> buka halaman post dengan pesan sukses.
     */
    public function store(PostRequest $request): RedirectResponse
    {
        try {
            $data = $request->safe()->except('image');

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('posts', 'public');
            }

            $post = Post::create($data);
        } catch (Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Post gagal disimpan. Silakan coba lagi.');
        }

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post berhasil dibuat.');
    }

    /** READ (detail): tampilkan satu post. */
    public function show(Post $post): View
    {
        return view('posts.show', compact('post'));
    }

    /** Tampilkan form edit yang sudah terisi data post. */
    public function edit(Post $post): View
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * UPDATE (ubah post)
     *   1. Data sudah dicek oleh PostRequest.
     *   2. Ambil data teks.
     *   3. JIKA ada gambar baru -> hapus gambar lama, simpan gambar baru.
     *   4. Perbarui post di database.
     *   5. JIKA error -> kembali ke form dengan pesan gagal.
     *   6. JIKA berhasil -> buka halaman post dengan pesan sukses.
     */
    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        try {
            $data = $request->safe()->except('image');

            if ($request->hasFile('image')) {
                $this->deleteImage($post);
                $data['image'] = $request->file('image')->store('posts', 'public');
            }

            $post->update($data);
        } catch (Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Post gagal diperbarui. Silakan coba lagi.');
        }

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post berhasil diperbarui.');
    }

    /**
     * DELETE (soft delete)
     *   1. Tandai post sebagai terhapus (kolom deleted_at diisi). Data masih ada di database.
     *   2. JIKA error -> kembali dengan pesan gagal.
     *   3. JIKA berhasil -> ke daftar post dengan pesan sukses.
     */
    public function destroy(Post $post): RedirectResponse
    {
        try {
            $post->delete();
        } catch (Throwable $e) {
            report($e);

            return back()->with('error', 'Post gagal dihapus.');
        }

        return redirect()->route('posts.index')
            ->with('success', 'Post dipindahkan ke tempat sampah.');
    }

    // ---------------- Tempat sampah ----------------

    /** Tampilkan hanya post yang sudah dihapus, yang terbaru dihapus di atas. */
    public function trash(): View
    {
        $posts = Post::onlyTrashed()->latest('deleted_at')->paginate(6);

        return view('posts.trash', compact('posts'));
    }

    /** Pulihkan: kosongkan deleted_at supaya post muncul lagi di daftar. */
    public function restore(Post $post): RedirectResponse
    {
        $post->restore();

        return redirect()->route('posts.trash')->with('success', 'Post berhasil dipulihkan.');
    }

    /**
     * Hapus permanen
     *   1. Hapus file gambar dari penyimpanan.
     *   2. Hapus baris post dari database selamanya.
     */
    public function forceDelete(Post $post): RedirectResponse
    {
        $this->deleteImage($post);
        $post->forceDelete();

        return redirect()->route('posts.trash')->with('success', 'Post dihapus permanen.');
    }

    /** Fungsi bantu: JIKA post punya gambar -> hapus file gambarnya. */
    private function deleteImage(Post $post): void
    {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
    }
}
