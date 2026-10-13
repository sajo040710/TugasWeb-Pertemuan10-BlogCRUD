<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model Post = wakil tabel "posts" di dalam kode.
 * SoftDeletes: hapus hanya menandai deleted_at, data tidak langsung hilang.
 */
class Post extends Model
{
    use HasFactory, SoftDeletes;

    // Hanya kolom ini yang boleh diisi massal (Post::create / update).
    // Tujuannya agar kolom lain tidak bisa diubah sembarangan lewat form.
    protected $fillable = ['title', 'content', 'image'];

    /**
     * Pencarian, dipakai sebagai ->search($kata).
     *   1. JIKA kata kunci kosong -> jangan filter apa pun.
     *   2. JIKA ada -> ambil post yang judulnya ATAU isinya mengandung kata itu.
     */
    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        $keyword = trim((string) $keyword);

        // filled() = tidak kosong. Kata kunci "0" tetap dihitung sebagai pencarian
        // (when($keyword) akan menganggap "0" sebagai kosong).
        return $query->when($keyword !== '', function (Builder $q) use ($keyword) {
            // Karakter khusus LIKE (% _ \) di-escape supaya dicari apa adanya.
            $like = '%'.addcslashes($keyword, '%_\\').'%';

            $q->where(fn (Builder $w) => $w
                ->where('title', 'like', $like)
                ->orWhere('content', 'like', $like));
        });
    }

    /**
     * $post->image_url
     *   JIKA ada gambar -> kembalikan alamat URL-nya (mengikuti host yang sedang dibuka,
     *                      jadi tetap benar walau APP_URL / port berbeda).
     *   JIKA tidak     -> kembalikan null.
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/'.$this->image) : null;
    }

    /** $post->excerpt = 120 karakter pertama isi post (cuplikan untuk kartu). */
    public function getExcerptAttribute(): string
    {
        return str($this->content)->limit(120)->toString();
    }
}
