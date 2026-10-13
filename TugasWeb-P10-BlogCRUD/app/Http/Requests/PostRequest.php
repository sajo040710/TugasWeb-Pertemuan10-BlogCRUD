<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * PostRequest — pemeriksa data form.
 * Dipakai oleh store (buat) dan update (ubah) karena aturannya sama.
 *
 * Algoritma:
 *   1. Form dikirim.
 *   2. Laravel mengecek setiap field dengan rules().
 *   3. JIKA ada yang salah -> kembali ke form, bawa pesan dari messages() dan isian lama (old).
 *   4. JIKA semua benar -> lanjut ke controller.
 */
class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // belum ada login, jadi semua orang boleh
    }

    public function rules(): array
    {
        return [
            'title'   => ['required', 'string', 'min:3', 'max:150'],   // wajib, 3-150 karakter
            'content' => ['required', 'string', 'min:10'],             // wajib, minimal 10 karakter
            'image'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // boleh kosong, gambar, maks 2 MB
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'   => 'Judul wajib diisi.',
            'title.min'        => 'Judul minimal :min karakter.',
            'title.max'        => 'Judul maksimal :max karakter.',
            'content.required' => 'Isi post wajib diisi.',
            'content.min'      => 'Isi post minimal :min karakter.',
            'image.image'      => 'File harus berupa gambar.',
            'image.mimes'      => 'Gambar harus berformat jpg, jpeg, png, atau webp.',
            'image.max'        => 'Ukuran gambar maksimal 2 MB.',
        ];
    }
}
