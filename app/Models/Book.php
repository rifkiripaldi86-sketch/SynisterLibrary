<?php

namespace App\Models;

// FILE: app/Models/Book.php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul_buku',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'isbn',
        'category_id',   // ← ganti dari 'kategori'
        'stok',
        'deskripsi',
        'cover_image',
    ];

    // ── Relasi ──────────────────────────────────────────

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function activeTransactions()
    {
        return $this->hasMany(Transaction::class)
                    ->whereIn('status', ['dipinjam', 'terlambat']);
    }

    // ── Accessor ─────────────────────────────────────────

    /** Nama kategori (aman meski category null) */
    public function getNamaKategoriAttribute(): string
    {
        return $this->category?->nama ?? '—';
    }

    /** Warna kategori */
    public function getWarnaKategoriAttribute(): string
    {
        return $this->category?->warna ?? '#8a9ab0';
    }

    // ── Stock helpers ────────────────────────────────────

    public function isAvailable(): bool
    {
        return $this->stok > 0;
    }

    public function decrementStock(): void
    {
        $this->decrement('stok');
    }

    public function incrementStock(): void
    {
        $this->increment('stok');
    }
}