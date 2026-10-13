{{-- FORM BERSAMA (dipakai halaman create dan edit)
     Algoritma untuk setiap field:
       1. Isi nilai awal dengan old('nama', data lama). Jadi kalau validasi gagal,
          ketikan pengguna tidak hilang.
       2. Jika field ini punya error (@error), beri garis merah dan tampilkan pesannya.
       3. Jika tidak, pakai garis normal. --}}
@php($editing = isset($post))

<div class="mb-6">
  <label for="title" class="mb-1.5 block text-sm font-semibold">Judul</label>
  <input type="text" id="title" name="title" value="{{ old('title', $post->title ?? '') }}"
         class="w-full border bg-white px-3 py-2.5 focus:border-ink focus:outline-none
                @error('title') border-red-600 @else border-line @enderror">
  @error('title')
    <p class="mt-1.5 text-sm text-red-700">{{ $message }}</p>
  @enderror
</div>

<div class="mb-6">
  <label for="content" class="mb-1.5 block text-sm font-semibold">Isi post</label>
  <textarea id="content" name="content" rows="8"
            class="w-full border bg-white px-3 py-2.5 focus:border-ink focus:outline-none
                   @error('content') border-red-600 @else border-line @enderror">{{ old('content', $post->content ?? '') }}</textarea>
  @error('content')
    <p class="mt-1.5 text-sm text-red-700">{{ $message }}</p>
  @enderror
</div>

<div class="mb-8">
  <label for="image" class="mb-1.5 block text-sm font-semibold">
    Gambar <span class="font-normal text-muted">(opsional, maks. 2 MB)</span>
  </label>

  {{-- Jika sedang edit dan post sudah punya gambar, tampilkan gambar lama --}}
  @if ($editing && $post->image_url)
    <img src="{{ $post->image_url }}" alt="Gambar saat ini" class="mb-3 h-32 border border-line object-cover">
    <p class="mb-2 text-xs text-muted">Pilih file baru untuk mengganti gambar di atas.</p>
  @endif

  <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
         class="block w-full text-sm text-muted file:mr-4 file:border-0 file:bg-ink file:px-4 file:py-2 file:text-paper hover:file:bg-rust">
  @error('image')
    <p class="mt-1.5 text-sm text-red-700">{{ $message }}</p>
  @enderror
</div>

<div class="flex gap-3">
  <button type="submit" class="bg-ink px-6 py-2.5 font-medium text-paper transition hover:bg-rust">
    {{ $editing ? 'Simpan perubahan' : 'Terbitkan post' }}
  </button>
  <a href="{{ $editing ? route('posts.show', $post) : route('posts.index') }}"
     class="border border-ink px-6 py-2.5 font-medium transition hover:bg-ink hover:text-paper">Batal</a>
</div>
