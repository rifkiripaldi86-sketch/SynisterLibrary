<?php

namespace App\Services;

use App\Models\AppNotification;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Ambil semua user dengan role admin.
     */
    private static function getAdminIds(): array
    {
        return User::where('role', 'admin')->pluck('id')->toArray();
    }

    /**
     * Kirim notifikasi ke satu user.
     */
    private static function sendToUser(int $userId, string $type, string $judul, string $pesan, ?int $transactionId = null): void
    {
        AppNotification::create([
            'user_id'        => $userId,
            'type'           => $type,
            'judul'          => $judul,
            'pesan'          => $pesan,
            'transaction_id' => $transactionId,
        ]);
    }

    /**
     * Kirim notifikasi ke semua admin.
     */
    private static function sendToAdmins(string $type, string $judul, string $pesan, ?int $transactionId = null): void
    {
        foreach (self::getAdminIds() as $adminId) {
            self::sendToUser($adminId, $type, $judul, $pesan, $transactionId);
        }
    }

    // ========== NOTIFIKASI UNTUK SISWA (tetap seperti semula) ==========

    public static function berhasilPinjam(Transaction $transaksi): void
    {
        $transaksi->loadMissing('book');

        // Notifikasi untuk siswa
        self::sendToUser(
            $transaksi->user_id,
            'berhasil_pinjam',
            'Peminjaman Berhasil',
            'Kamu berhasil meminjam buku "' . $transaksi->book->judul_buku . '". Harap dikembalikan sebelum ' . $transaksi->tanggal_kembali_rencana->format('d M Y') . '.',
            $transaksi->id
        );

        // Notifikasi untuk admin
        self::sendToAdmins(
            'admin_pinjam_baru',
            'Peminjaman Baru',
            'Siswa ' . $transaksi->user->name . ' meminjam buku "' . $transaksi->book->judul_buku . '".',
            $transaksi->id
        );
    }

    public static function berhasilKembali(Transaction $transaksi): void
    {
        $transaksi->loadMissing('book');

        $pesanSiswa = 'Kamu berhasil mengembalikan buku "' . $transaksi->book->judul_buku . '".';
        if ($transaksi->denda > 0) {
            $pesanSiswa .= ' Denda keterlambatan: Rp ' . number_format($transaksi->denda, 0, ',', '.') . '.';
        }

        // Notifikasi untuk siswa
        self::sendToUser(
            $transaksi->user_id,
            'berhasil_kembali',
            'Pengembalian Berhasil',
            $pesanSiswa,
            $transaksi->id
        );

        // Notifikasi untuk admin
        $pesanAdmin = 'Siswa ' . $transaksi->user->name . ' mengembalikan buku "' . $transaksi->book->judul_buku . '".';
        if ($transaksi->denda > 0) {
            $pesanAdmin .= ' Denda: Rp ' . number_format($transaksi->denda, 0, ',', '.');
        }
        self::sendToAdmins(
            'admin_kembali',
            'Pengembalian Buku',
            $pesanAdmin,
            $transaksi->id
        );
    }

    public static function cekDeadlineHarian(): int
    {
        $today  = Carbon::today();
        $jumlah = 0;

        $transaksiAktif = Transaction::with(['user', 'book'])
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->get();

        foreach ($transaksiAktif as $t) {
            $selisihHari = $today->diffInDays($t->tanggal_kembali_rencana, false);

            if ($selisihHari === 3 || $selisihHari === 1) {
                $hari = ($selisihHari === 3) ? 3 : 1;
                if (self::kirimJatuhTempo($t, $hari)) $jumlah++;
            } elseif ($selisihHari < 0) {
                if (self::kirimTerlambat($t, abs($selisihHari))) $jumlah++;
            }
        }

        return $jumlah;
    }

    private static function kirimJatuhTempo(Transaction $transaksi, int $hariLagi): bool
    {
        $sudahAda = AppNotification::where('user_id', $transaksi->user_id)
            ->where('transaction_id', $transaksi->id)
            ->where('type', 'jatuh_tempo')
            ->whereDate('created_at', today())
            ->exists();

        if ($sudahAda) return false;

        // Notifikasi ke siswa
        self::sendToUser(
            $transaksi->user_id,
            'jatuh_tempo',
            'Jatuh Tempo dalam ' . $hariLagi . ' Hari',
            'Buku "' . $transaksi->book->judul_buku . '" harus dikembalikan dalam ' . $hariLagi . ' hari lagi (' . $transaksi->tanggal_kembali_rencana->format('d M Y') . ').',
            $transaksi->id
        );

        // Notifikasi ke admin (peringatan)
        self::sendToAdmins(
            'admin_jatuh_tempo',
            'Peringatan Jatuh Tempo',
            'Siswa ' . $transaksi->user->name . ' harus mengembalikan buku "' . $transaksi->book->judul_buku . '" dalam ' . $hariLagi . ' hari.',
            $transaksi->id
        );

        return true;
    }

    private static function kirimTerlambat(Transaction $transaksi, int $hariTerlambat): bool
    {
        $sudahAda = AppNotification::where('user_id', $transaksi->user_id)
            ->where('transaction_id', $transaksi->id)
            ->where('type', 'terlambat')
            ->whereDate('created_at', today())
            ->exists();

        if ($sudahAda) return false;

        $estimasiDenda = $hariTerlambat * 1000;

        // Notifikasi ke siswa
        self::sendToUser(
            $transaksi->user_id,
            'terlambat',
            'Buku Terlambat ' . $hariTerlambat . ' Hari',
            'Buku "' . $transaksi->book->judul_buku . '" sudah terlambat ' . $hariTerlambat . ' hari. Estimasi denda: Rp ' . number_format($estimasiDenda, 0, ',', '.') . '. Segera kembalikan!',
            $transaksi->id
        );

        // Notifikasi ke admin (lebih urgent)
        self::sendToAdmins(
            'admin_terlambat',
            'Buku Terlambat!',
            'Siswa ' . $transaksi->user->name . ' terlambat ' . $hariTerlambat . ' hari mengembalikan buku "' . $transaksi->book->judul_buku . '". Denda estimasi Rp ' . number_format($estimasiDenda, 0, ',', '.'),
            $transaksi->id
        );

        return true;
    }

    // ========== NOTIFIKASI STOK BUKU MENIPIS (untuk admin) ==========

    /**
     * Cek semua buku dengan stok <= batas minimal (default 2)
     * Kirim notifikasi ke admin setiap hari (jika kondisi terpenuhi)
     * Panggil dari command: notif:cek-stok
     */
    public static function cekStokMenipis(int $batasMinimal = 2): int
    {
        $bukuMenipis = \App\Models\Book::where('stok', '<=', $batasMinimal)
            ->where('stok', '>', 0)
            ->get();

        $jumlahNotif = 0;

        foreach ($bukuMenipis as $buku) {
            // Cek apakah sudah ada notifikasi untuk buku ini hari ini
            $sudahAda = AppNotification::where('type', 'stok_menipis')
                ->where('judul', 'like', "%{$buku->judul_buku}%")
                ->whereDate('created_at', today())
                ->exists();

            if (!$sudahAda) {
                self::sendToAdmins(
                    'stok_menipis',
                    'Stok Buku Menipis',
                    "Buku '{$buku->judul_buku}' tersisa {$buku->stok} eksemplar. Segera tambah stok.",
                    null
                );
                $jumlahNotif++;
            }
        }

        return $jumlahNotif;
    }
}
