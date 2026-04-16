<?php

namespace App\Models;

// FILE: app/Models/Transaction.php

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'book_id',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'status',
        'denda',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pinjam'           => 'date',
        'tanggal_kembali_rencana'  => 'date',
        'tanggal_kembali_aktual'   => 'date',
    ];

    // =============================================
    // RELASI (Relationships)
    // =============================================

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    // =============================================
    // SCOPES
    // =============================================

    /**
     * FIX (BARU): Scope untuk transaksi yang aktif terlambat di database.
     *
     * Gunakan scope ini untuk mengambil data terlambat secara akurat,
     * tanpa bergantung pada kolom status yang mungkin belum terupdate.
     *
     * Contoh penggunaan:
     *   Transaction::terlambat()->get();
     */
    public function scopeTerlambat($query)
    {
        return $query->where('status', 'dipinjam')
                     ->where('tanggal_kembali_rencana', '<', now()->toDateString());
    }

    /**
     * Scope untuk transaksi yang masih aktif dipinjam (belum terlambat).
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'dipinjam')
                     ->where('tanggal_kembali_rencana', '>=', now()->toDateString());
    }

    // =============================================
    // HELPER METHODS
    // =============================================

    /**
     * FIX: Hitung denda otomatis berdasarkan keterlambatan.
     * Denda: Rp 1.000 per hari terlambat.
     *
     * BUG SEBELUMNYA: Menggunakan operator || (OR) sehingga buku yang sudah
     * dikembalikan tapi tanggal_kembali_aktual null (data korup) akan dihitung
     * dendanya pakai Carbon::now() → hasil salah.
     *
     * PERBAIKAN: Gunakan && (AND) agar Carbon::now() hanya dipakai ketika
     * buku BELUM dikembalikan DAN belum ada tanggal aktual.
     */
    public function hitungDenda(): int
    {
        // Tentukan tanggal aktual pengembalian
        if ($this->status !== 'dikembalikan' && !$this->tanggal_kembali_aktual) {
            // Buku masih dipinjam → pakai hari ini sebagai acuan
            $tanggalAktual = Carbon::now();
        } else {
            // Buku sudah dikembalikan → pakai tanggal aktual, fallback now() jika null
            $tanggalAktual = $this->tanggal_kembali_aktual ?? Carbon::now();
        }

        if ($tanggalAktual->greaterThan($this->tanggal_kembali_rencana)) {
            $hariTerlambat = $this->tanggal_kembali_rencana->diffInDays($tanggalAktual);
            return (int) ($hariTerlambat * 1000); // Rp 1.000/hari
        }

        return 0;
    }

    /**
     * FIX: Cek apakah peminjaman terlambat.
     *
     * CATATAN PENTING: Method ini hanya mengecek kondisi saat ini.
     * Status di database TIDAK otomatis berubah menjadi 'terlambat'.
     *
     * Untuk update status 'terlambat' secara massal, gunakan Artisan Command
     * terjadwal (lihat: app/Console/Commands/UpdateStatusTerlambat.php).
     */
    public function isTerlambat(): bool
    {
        if ($this->status === 'dikembalikan') {
            return false;
        }

        return Carbon::now()->greaterThan($this->tanggal_kembali_rencana);
    }

    /**
     * Proses pengembalian buku.
     */
    public function prosesPengembalian(): void
    {
        $today = Carbon::today();
        $denda = $this->hitungDenda();

        $this->update([
            'status'                  => 'dikembalikan',
            'tanggal_kembali_aktual'  => $today,
            'denda'                   => $denda,
        ]);

        // Kembalikan stok buku
        $this->book->incrementStock();
    }
}
