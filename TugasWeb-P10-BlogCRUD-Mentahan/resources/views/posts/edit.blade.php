@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
  <h1 class="mb-10 font-display text-4xl font-extrabold">Edit Post</h1>

  {{-- Algoritma:
         1. Laravel mencari post dari id di URL (Route Model Binding), hasilnya ada di $post.
         2. Form diisi dengan data $post.
         3. Browser hanya bisa POST, jadi @method('PUT') memberi tahu Laravel bahwa ini update.
         4. @csrf dan enctype tetap wajib. --}}
  <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data" class="max-w-2xl">
    @csrf
    @method('PUT')
    @include('posts._form')
  </form>
@endsection
