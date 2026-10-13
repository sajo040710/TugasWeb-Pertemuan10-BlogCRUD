# Tugas Rutin 10 — Blog CRUD Laravel

Mata kuliah **Pemrograman Web** · Hardiman G. Hutauruk · Ilmu Komputer, Universitas Negeri Medan

Aplikasi blog sederhana. Pengguna bisa **membuat, membaca, mengubah, dan menghapus** post
(CRUD = Create, Read, Update, Delete). Dibuat dengan Laravel, Blade, dan Eloquent.

---

## 1. Gambaran umum: apa yang terjadi saat halaman dibuka?

Setiap permintaan dari browser selalu melewati urutan yang sama:

```
1. Browser membuka sebuah URL                    (misal /posts/3)
2. Route mencocokkan URL dengan method           (posts.show -> PostController@show)
3. Controller mengambil / mengubah data          lewat model Post
4. Controller mengirim data ke View              (file .blade.php)
5. View menyusun HTML                            lalu dikirim kembali ke browser
```

Pembagian tugas:

| Bagian | Tugasnya |
|--------|----------|
| Route | Menentukan URL mana memanggil method mana |
| Controller | Menjalankan langkah-langkah (ambil, simpan, ubah, hapus) |
| Request | Memeriksa data form sebelum diproses |
| Model | Berhubungan dengan tabel `posts` di database |
| View | Menampilkan data sebagai HTML |

---

## 2. Algoritma tiap fitur

### 2.1 Melihat daftar post (Read)
```
1. Ambil kata kunci pencarian dari ?q= (boleh kosong)
2. Ambil post dari database
     JIKA ada kata kunci -> hanya post yang judul ATAU isinya mengandung kata itu
3. Urutkan dari yang terbaru
4. Bagi menjadi halaman berisi 6 post
5. Tampilkan satu kartu per post + nomor halaman
     JIKA tidak ada post -> tampilkan "Belum ada post"
```

### 2.2 Menambah post (Create)
```
1. Pengguna membuka form kosong (/posts/create)
2. Pengguna mengisi judul, isi, dan (opsional) gambar, lalu menekan "Terbitkan"
3. PostRequest memeriksa data:
     JIKA ada yang salah -> kembali ke form, tampilkan pesan per field,
                            isian lama tetap terisi (old input)
4. JIKA ada file gambar -> simpan file ke storage/app/public/posts
5. Simpan post ke database
6. JIKA berhasil -> buka halaman post + pesan "Post berhasil dibuat"
   JIKA gagal    -> kembali ke form + pesan "Post gagal disimpan"
```

### 2.3 Melihat satu post (Read detail)
```
1. Laravel membaca id dari URL, mencari post dengan id itu
     JIKA tidak ditemukan -> tampil halaman 404
2. Tampilkan gambar (jika ada), judul, tanggal, dan isi post
```
> Ini disebut **Route Model Binding**: parameter `Post $post` terisi otomatis dari id di URL.

### 2.4 Mengubah post (Update)
```
1. Buka form edit, isinya sudah terisi data post lama
2. Pengguna mengubah data lalu menekan "Simpan perubahan"
3. PostRequest memeriksa data (aturan sama seperti menambah post)
4. JIKA ada gambar baru -> hapus gambar lama, simpan gambar baru
5. Perbarui post di database
6. Tampilkan pesan sukses / gagal
```
> Browser hanya mengenal GET dan POST, jadi form edit memakai `@method('PUT')`
> untuk memberi tahu Laravel bahwa ini permintaan update.

### 2.5 Menghapus post (Delete, soft delete)
```
1. Pengguna menekan "Hapus" dan menyetujui kotak konfirmasi
2. Isi kolom deleted_at pada post  (data TIDAK dihapus dari database)
3. Post tidak lagi muncul di daftar, tetapi muncul di Tempat Sampah
```

### 2.6 Tempat sampah
```
Lihat     : tampilkan hanya post yang deleted_at-nya terisi
Pulihkan  : kosongkan deleted_at -> post muncul lagi di daftar
Hapus permanen :
     1. Hapus file gambar post
     2. Hapus baris post dari database selamanya
```

### 2.7 Validasi form
| Field | Aturan |
|-------|--------|
| Judul | wajib, 3 sampai 150 karakter |
| Isi | wajib, minimal 10 karakter |
| Gambar | boleh kosong; jika diisi harus gambar jpg / jpeg / png / webp, maksimal 2 MB |

```
Untuk setiap field:
  JIKA melanggar aturan -> tampilkan pesan error di bawah field dan beri garis merah
  JIKA tidak            -> lanjut
```

### 2.8 Pesan notifikasi (flash message)
```
1. Controller menyimpan pesan di session   ->with('success', '...') atau ->with('error', '...')
2. Layout mengecek session
     JIKA ada pesan -> tampilkan lewat komponen <x-alert>
3. Pesan hanya tampil sekali, hilang saat halaman dimuat ulang
```

### 2.9 Komponen Blade
| Komponen | Fungsi | Cara pakai |
|----------|--------|-----------|
| `alert` | Kotak pesan hijau (sukses) / merah (error) | `<x-alert type="success">Pesan</x-alert>` |
| `card` | Kartu post: gambar, judul, isi | `<x-card :title="..." :href="..." :image="...">isi</x-card>` |

---

## 3. Daftar route

| Method | URL | Nama route | Fungsi |
|--------|-----|------------|--------|
| GET | `/posts` | `posts.index` | Daftar post |
| GET | `/posts/create` | `posts.create` | Form tambah |
| POST | `/posts` | `posts.store` | Simpan post baru |
| GET | `/posts/{post}` | `posts.show` | Detail post |
| GET | `/posts/{post}/edit` | `posts.edit` | Form ubah |
| PUT | `/posts/{post}` | `posts.update` | Simpan perubahan |
| DELETE | `/posts/{post}` | `posts.destroy` | Hapus (ke tempat sampah) |
| GET | `/posts/trash` | `posts.trash` | Tempat sampah |
| PATCH | `/posts/{post}/restore` | `posts.restore` | Pulihkan |
| DELETE | `/posts/{post}/force` | `posts.force-delete` | Hapus permanen |

Tujuh route pertama dibuat sekaligus oleh satu baris: `Route::resource('posts', PostController::class)`.

Route tempat sampah ditulis **sebelum** `Route::resource`. Jika sesudahnya, URL `/posts/trash`
akan dianggap sebagai post dengan id "trash".

## 4. Fitur tambahan

- **Pencarian**: `/posts?q=kata`
- **Soft delete**: post yang dihapus bisa dipulihkan
- **Upload gambar**: disimpan di `storage/app/public/posts`

---

## 5. Cara instalasi

Folder ini hanya berisi **file yang dibuat / diubah**, bukan seluruh project Laravel.

1. Buat project Laravel baru:

```bash
composer create-project laravel/laravel TugasWeb-P10-BlogCRUD
```

2. **Salin isi folder ini** (`app`, `database`, `resources`, `routes`, `tests`, `.env.example`)
   ke dalam project tadi. Pilih *Replace / timpa* jika ditanya.
3. Buat database kosong bernama `tugas10_blogcrud` di phpMyAdmin.
4. Jalankan perintah berikut di folder project:

```bash
cd TugasWeb-P10-BlogCRUD
copy .env.example .env          # Linux/Mac: cp .env.example .env
php artisan key:generate
php artisan migrate --seed      # buat tabel + 15 post contoh
php artisan storage:link        # agar gambar upload bisa dibuka
php artisan serve
```

Buka <http://127.0.0.1:8000> (otomatis menuju `/posts`).

> Setelah laptop dimatikan: nyalakan MySQL, buka terminal di folder project, jalankan `php artisan serve` lagi.

## 6. Menjalankan test

```bash
php artisan test --filter=PostCrudTest
```

Test memakai SQLite sementara di memori, jadi database MySQL tidak berubah.
Butuh ekstensi PHP `pdo_sqlite` dan `gd`.

Yang diuji: pagination, pencarian, error validasi, tambah post + gambar, ubah post,
hapus + pulihkan, dan 404 untuk id yang tidak ada.

## 7. Letak file

```
app/
├── Http/Controllers/PostController.php   ← langkah-langkah CRUD
├── Http/Requests/PostRequest.php         ← aturan validasi + pesan error
└── Models/Post.php                       ← kolom, pencarian, gambar, soft delete
database/
├── migrations/..._create_posts_table.php ← struktur tabel
├── factories/PostFactory.php             ← resep data contoh
└── seeders/DatabaseSeeder.php            ← buat 15 post contoh
resources/views/
├── layouts/app.blade.php                 ← kerangka halaman (navigasi, notifikasi, footer)
├── components/{alert,card}.blade.php     ← komponen
└── posts/{index,create,edit,show,trash,_form}.blade.php
routes/web.php                            ← daftar URL
tests/Feature/PostCrudTest.php            ← pengujian otomatis
```

## 8. Keamanan

| Ancaman | Cara mengatasi |
|---------|----------------|
| Form dipalsukan dari situs lain | `@csrf` di semua form selain GET |
| Pengguna mengubah kolom yang tidak seharusnya | `$fillable` di model + controller hanya memakai data yang lolos validasi |
| Kode jahat disisipkan di teks (XSS) | `{{ }}` otomatis meng-escape; isi post memakai `nl2br(e(...))` |
| Upload file berbahaya | Harus gambar jpg / jpeg / png / webp, maksimal 2 MB |

## 9. Bukti (screenshot)

Taruh di folder `docs/` lalu lampirkan saat pengumpulan: daftar post (pagination), form dengan
error validasi (isian lama masih terlihat), pesan sukses, hasil `php artisan route:list`, dan
halaman tempat sampah.
