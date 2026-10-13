<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // up(): dijalankan saat `php artisan migrate` -> buat tabel posts.
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->text('content');
            $table->string('image')->nullable(); // lokasi file gambar, boleh kosong
            $table->timestamps();
            $table->softDeletes();               // kolom deleted_at untuk soft delete
        });
    }

    // down(): dijalankan saat rollback -> hapus tabel posts jika ada.
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
