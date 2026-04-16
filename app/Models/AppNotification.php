<?php

namespace App\Models;

// FILE: app/Models/AppNotification.php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppNotification extends Model
{
    use HasFactory;

    protected $table = 'app_notifications';

    protected $fillable = [
        'user_id',
        'type',
        'judul',
        'pesan',
        'transaction_id',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // ── Relasi ──────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // ── Helper ──────────────────────────────────────────

    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Mendapatkan ikon Bootstrap Icons untuk tipe notifikasi.
     */
    public function icon(): string
    {
        return match($this->type) {
            'jatuh_tempo'           => 'bi-clock-history',
            'terlambat'             => 'bi-alarm-fill',
            'berhasil_pinjam'       => 'bi-bookmark-check-fill',
            'berhasil_kembali'      => 'bi-check-circle-fill',
            'admin_pinjam_baru'     => 'bi-person-plus-fill',
            'admin_kembali'         => 'bi-arrow-return-left',
            'admin_jatuh_tempo'     => 'bi-hourglass-split',
            'admin_terlambat'       => 'bi-exclamation-octagon-fill',
            'stok_menipis'          => 'bi-exclamation-diamond-fill',
            default                 => 'bi-bell-fill',
        };
    }

    /**
     * Mendapatkan warna Bootstrap untuk tipe notifikasi.
     * (primary, secondary, success, danger, warning, info, light, dark)
     */
    public function warna(): string
    {
        return match($this->type) {
            'jatuh_tempo'           => 'warning',
            'terlambat'             => 'danger',
            'berhasil_pinjam'       => 'success',
            'berhasil_kembali'      => 'success',
            'admin_pinjam_baru'     => 'primary',
            'admin_kembali'         => 'info',
            'admin_jatuh_tempo'     => 'warning',
            'admin_terlambat'       => 'danger',
            'stok_menipis'          => 'danger',
            default                 => 'secondary',
        };
    }
}
