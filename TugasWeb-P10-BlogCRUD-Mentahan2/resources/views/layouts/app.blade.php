<!doctype html>
{{-- LAYOUT UTAMA
     Algoritma:
       1. Semua halaman memakai @extends('layouts.app').
       2. Halaman mengisi @section('title') dan @section('content').
       3. Layout menampilkan: navigasi -> pesan notifikasi -> isi halaman -> footer.
     Style: Tailwind CDN (tanpa build), tema terang bergaya kertas. --}}
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Blog CRUD') · Blog CRUD</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter', 'system-ui', 'sans-serif'],
            display: ['Fraunces', 'Georgia', 'serif'],
          },
          colors: {
            paper: '#faf6ef',   // latar halaman
            ink:   '#1c1917',   // teks utama
            muted: '#78716c',   // teks sekunder
            line:  '#e4dccd',   // garis tipis
            rust:  '#b8451f',   // warna aksen
          },
        },
      },
    }
  </script>
</head>
<body class="min-h-screen bg-paper font-sans text-ink antialiased">

  {{-- Navigasi --}}
  <header class="border-b border-line">
    <nav class="mx-auto flex max-w-5xl items-center justify-between px-6 py-5">
      <a href="{{ route('posts.index') }}" class="font-display text-2xl font-extrabold tracking-tight">
        Blog<span class="text-rust">.</span>CRUD
      </a>

      <div class="flex items-center gap-6 text-sm font-medium">
        <a href="{{ route('posts.index') }}"
           class="hover:text-rust {{ request()->routeIs('posts.index') ? 'text-rust underline underline-offset-8' : 'text-muted' }}">Semua Post</a>
        <a href="{{ route('posts.trash') }}"
           class="hover:text-rust {{ request()->routeIs('posts.trash') ? 'text-rust underline underline-offset-8' : 'text-muted' }}">Tempat Sampah</a>
        <a href="{{ route('posts.create') }}"
           class="bg-ink px-4 py-2 text-paper transition hover:bg-rust">Tulis Post</a>
      </div>
    </nav>
  </header>

  <main class="mx-auto max-w-5xl px-6 py-12">
    {{-- Notifikasi: jika ada pesan sukses / gagal / error validasi, tampilkan lewat <x-alert> --}}
    @if (session('success'))
      <x-alert type="success">{{ session('success') }}</x-alert>
    @endif
    @if (session('error'))
      <x-alert type="error">{{ session('error') }}</x-alert>
    @endif
    @if ($errors->any())
      <x-alert type="error">Periksa kembali isian form — ada {{ $errors->count() }} kesalahan.</x-alert>
    @endif

    @yield('content')
  </main>

  <footer class="mx-auto max-w-5xl border-t border-line px-6 py-8 text-center text-xs text-muted">
    Tugas Rutin 10 — Blog CRUD Laravel · Pemrograman Web · Hardiman G. Hutauruk
  </footer>
</body>
</html>
