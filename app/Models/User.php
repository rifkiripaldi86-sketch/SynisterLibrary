<?php

namespace App\Models;

// FILE: app/Models/User.php

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;
    // JANGAN pakai: use Notifiable;
    // Trait Notifiable akan query tabel 'notifications' Laravel bawaan
    // dengan kolom notifiable_type yang tidak ada di tabel kita.

    /**
     * FIX: Tambahkan 'email' ke $fillable.
     * Sebelumnya kolom 'email' ada di migration tapi tidak bisa diisi
     * via mass assignment karena tidak ada di $fillable.
     */
    protected $fillable = [
        'name',
        'username',
        'email',       // FIX: tambahkan email
        'no_induk',
        'kelas',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // ── Role helpers ────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    // ── Relasi Transaksi ─────────────────────────────────

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function activeBorrows()
    {
        return $this->hasMany(Transaction::class)
                    ->whereIn('status', ['dipinjam', 'terlambat']);
    }

    // ── Relasi Notifikasi ────────────────────────────────

    /**
     * FIX: Rename dari notifications() → appNotifications()
     *
     * Method 'notifications()' adalah nama reserved Laravel untuk sistem
     * notifikasi bawaan (trait Notifiable). Meski trait-nya tidak dipakai
     * sekarang, nama ini tetap rawan konflik jika suatu saat trait ditambahkan.
     *
     * PENTING: Update semua penggunaan di Controller/View dari:
     *   $user->notifications()    → $user->appNotifications()
     *   $user->unreadNotifications() → $user->unreadAppNotifications()
     */
    public function appNotifications()
    {
        return $this->hasMany(AppNotification::class, 'user_id');
    }

    /** Notifikasi yang belum dibaca */
    public function unreadAppNotifications()
    {
        return $this->hasMany(AppNotification::class, 'user_id')
                    ->where('is_read', false);
    }

    /** Jumlah notif belum dibaca (untuk badge) */
    public function unreadNotificationsCount(): int
    {
        return $this->unreadAppNotifications()->count();
    }

    public function hasOverdueLoan(): bool
    {
        return Transaction::where('user_id', $this->id)
            ->where('status', '!=', 'dikembalikan')
            ->where(function ($query) {
                $query->where('status', 'terlambat')
                      ->orWhereDate('tanggal_kembali_rencana', '<', today());
            })
            ->exists();
    }
}
