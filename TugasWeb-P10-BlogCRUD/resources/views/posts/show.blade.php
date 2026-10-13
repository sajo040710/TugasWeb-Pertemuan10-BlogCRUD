@extends('layouts.app')

@section('title', $post->title)

@section('content')
  {{-- Algoritma halaman detail:
         1. Laravel mengambil satu post berdasarkan id di URL, hasilnya di $post.
         2. Jika id tidak ada, Laravel otomatis menampilkan 404.
         3. Tampilkan gambar (jika ada), judul, tanggal, lalu isi post.
         4. Sediakan tombol Edit dan Hapus. --}}
  <a href="{{ route('posts.index') }}" class="text-sm text-muted hover:text-rust">&larr; Semua post</a>

  <article class="mt-6 max-w-3xl">
    @if ($post->image_url)
      <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="mb-8 max-h-96 w-full border border-line object-cover">
    @endif

    <h1 class="font-display text-4xl font-extrabold leading-tight">{{ $post->title }}</h1>
    <p class="mt-3 text-sm text-muted">
      Diterbitkan {{ $post->created_at->translatedFormat('l, d F Y') }}
      @if ($post->updated_at->gt($post->created_at))
        · diperbarui {{ $post->updated_at->diffForHumans() }}
      @endif
    </p>

    {{-- e() mengamankan teks dari kode berbahaya (XSS), nl2br() mengubah baris baru jadi <br> --}}
    <div class="mt-8 text-lg leading-relaxed">{!! nl2br(e($post->content)) !!}</div>

    <div class="mt-12 flex gap-3 border-t border-line pt-6">
      <a href="{{ route('posts.edit', $post) }}"
         class="border border-ink px-5 py-2 text-sm font-medium transition hover:bg-ink hover:text-paper">Edit</a>

      <form method="POST" action="{{ route('posts.destroy', $post) }}"
            onsubmit="return confirm('Pindahkan post ini ke tempat sampah?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="border border-red-700 px-5 py-2 text-sm font-medium text-red-700 transition hover:bg-red-700 hover:text-white">
          Hapus
        </button>
      </form>
    </div>
  </article>
@endsection
