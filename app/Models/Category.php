<?php

namespace App\Models;

// FILE: app/Models/Category.php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'warna',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->nama);
            }
        });

        /**
         * FIX: Slug hanya di-regenerate jika kolom 'nama' berubah.
         * Sebelumnya slug selalu di-overwrite saat update field apapun,
         * yang bisa merusak URL lama yang sudah tersimpan di tempat lain.
         */
        static::updating(function ($category) {
            if ($category->isDirty('nama')) {
                $category->slug = Str::slug($category->nama);
            }
        });
    }

    // ── Relasi ──────────────────────────────────────────

    public function books()
    {
        return $this->hasMany(Book::class, 'category_id');
    }
}
