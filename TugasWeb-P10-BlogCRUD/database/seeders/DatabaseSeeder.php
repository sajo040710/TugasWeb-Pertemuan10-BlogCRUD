<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 15 post contoh.
        // 15 > 6 per halaman, jadi nomor halaman langsung terlihat (3 halaman).
        Post::factory(15)->create();
    }
}
