@extends('layouts.app')

@section('title', 'Semua Post')

@section('content')
  {{-- Algoritma halaman daftar:
         1. Controller mengirim $posts (maksimal 6 per halaman, sudah difilter oleh ?q=).
         2. Untuk setiap post, tampilkan satu kartu.
         3. Jika $posts kosong, tampilkan pesan "belum ada post".
         4. Di bawah daftar, tampilkan tombol nomor halaman. --}}
  <div class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <h1 class="font-display text-4xl font-extrabold">Semua Post</h1>
      <p class="mt-1 text-sm text-muted">{{ $posts->total() }} post ditemukan</p>
    </div>

    {{-- Pencarian: method GET sehingga tidak perlu @csrf --}}
    <form method="GET" action="{{ route('posts.index') }}" class="flex gap-2">
      <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari judul atau isi…"
             class="w-64 border border-line bg-white px-3 py-2 text-sm focus:border-ink focus:outline-none">
      <button class="border border-ink px-4 py-2 text-sm font-medium transition hover:bg-ink hover:text-paper">Cari</button>
    </form>
  </div>

  <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @forelse ($posts as $post)
      <x-card :title="$post->title" :href="route('posts.show', $post)" :image="$post->image_url">
        <p class="mb-4 flex-1 text-sm leading-relaxed text-muted">{{ $post->excerpt }}</p>

        <div class="flex items-center justify-between border-t border-line pt-3 text-xs text-muted">
          <span>{{ $post->created_at->translatedFormat('d M Y') }}</span>
          <div class="flex items-center gap-3 font-medium">
            <a href="{{ route('posts.edit', $post) }}" class="text-ink hover:text-rust hover:underline">Edit</a>

            {{-- Hapus: @csrf + @method('DELETE') --}}
            <form method="POST" action="{{ route('posts.destroy', $post) }}"
                  onsubmit="return confirm('Pindahkan post ini ke tempat sampah?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="text-red-700 hover:underline">Hapus</button>
            </form>
          </div>
        </div>
      </x-card>
    @empty
      <p class="col-span-full border border-dashed border-muted/50 p-10 text-center text-muted">
        Belum ada post{{ request('q') ? ' yang cocok dengan "'.e(request('q')).'"' : '' }}.
        <a href="{{ route('posts.create') }}" class="text-rust underline">Tulis post pertama</a>.
      </p>
    @endforelse
  </div>

  {{-- Nomor halaman --}}
  <div class="mt-10">
    {{ $posts->links() }}
  </div>
@endsection
