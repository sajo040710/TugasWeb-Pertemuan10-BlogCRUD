<?php

// ====================================================================
// routes/web.php — Blog CRUD
//
// Fungsi file ini: mencocokkan alamat URL dengan method di controller.
//
// Algoritma:
//   1. Alamat "/" dialihkan ke "/posts".
//   2. Daftarkan route tambahan (tempat sampah, pulihkan, hapus permanen).
//   3. Daftarkan 7 route CRUD sekaligus dengan Route::resource.
// ====================================================================

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

// Langkah 1
Route::redirect('/', '/posts');

// Langkah 2
// PENTING: route ini harus ditulis SEBELUM resource.
// Kalau sesudahnya, "posts/trash" akan salah dikira posts/{post} dengan id "trash".
Route::get('posts/trash', [PostController::class, 'trash'])->name('posts.trash');
Route::patch('posts/{post}/restore', [PostController::class, 'restore'])
    ->withTrashed()->name('posts.restore');
Route::delete('posts/{post}/force', [PostController::class, 'forceDelete'])
    ->withTrashed()->name('posts.force-delete');

// Langkah 3
// Menghasilkan: index, create, store, show, edit, update, destroy
// beserta nama route posts.index, posts.create, dst.
Route::resource('posts', PostController::class);
