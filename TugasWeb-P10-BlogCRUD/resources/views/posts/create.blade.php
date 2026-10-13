@extends('layouts.app')

@section('title', 'Tulis Post')

@section('content')
  <h1 class="mb-10 font-display text-4xl font-extrabold">Tulis Post Baru</h1>

  {{-- Algoritma:
         1. Pengguna mengisi form lalu menekan "Terbitkan".
         2. Data dikirim (POST) ke route posts.store.
         3. @csrf wajib ada agar form diterima Laravel.
         4. enctype="multipart/form-data" wajib agar file gambar ikut terkirim. --}}
  <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="max-w-2xl">
    @csrf
    @include('posts._form')
  </form>
@endsection
