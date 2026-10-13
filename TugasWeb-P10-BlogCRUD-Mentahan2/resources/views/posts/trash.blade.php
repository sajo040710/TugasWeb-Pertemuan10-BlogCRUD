@extends('layouts.app')

@section('title', 'Tempat Sampah')

@section('content')
  {{-- Algoritma halaman tempat sampah:
         1. Controller mengirim post yang sudah dihapus (soft delete).
         2. Setiap post punya dua tombol: Pulihkan dan Hapus permanen.
         3. Jika kosong, tampilkan "Tempat sampah kosong". --}}
  <h1 class="font-display text-4xl font-extrabold">Tempat Sampah</h1>
  <p class="mb-10 mt-1 text-sm text-muted">Post yang dihapus masih bisa dipulihkan dari sini.</p>

  <div class="space-y-3">
    @forelse ($posts as $post)
      <div class="flex flex-col gap-3 border border-line bg-white p-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <p class="font-display text-lg font-semibold">{{ $post->title }}</p>
          <p class="text-xs text-muted">Dihapus {{ $post->deleted_at->diffForHumans() }}</p>
        </div>

        <div class="flex gap-4 text-sm font-medium">
          <form method="POST" action="{{ route('posts.restore', $post) }}">
            @csrf
            @method('PATCH')
            <button type="submit" class="text-emerald-800 hover:underline">Pulihkan</button>
          </form>

          <form method="POST" action="{{ route('posts.force-delete', $post) }}"
                onsubmit="return confirm('Hapus permanen? Tindakan ini tidak bisa dibatalkan.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-700 hover:underline">Hapus permanen</button>
          </form>
        </div>
      </div>
    @empty
      <p class="border border-dashed border-muted/50 p-10 text-center text-muted">
        Tempat sampah kosong.
      </p>
    @endforelse
  </div>

  <div class="mt-10">{{ $posts->links() }}</div>
@endsection
