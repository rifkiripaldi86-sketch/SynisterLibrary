<?php

namespace App\Console\Commands;

// FILE: app/Console/Commands/CekDeadlineNotifikasi.php
// Jalankan manual: php artisan notif:cek-deadline
// Jadwalkan harian di app/Console/Kernel.php

use App\Services\NotificationService;
use Illuminate\Console\Command;

class CekDeadlineNotifikasi extends Command
{
    protected $signature   = 'notif:cek-deadline';
    protected $description = 'Cek semua transaksi aktif dan kirim notifikasi jatuh tempo / keterlambatan';

    public function handle(): void
    {
        $this->info('Mengecek deadline transaksi...');

        try {
            // ✅ FIX #1: Nama method sudah cocok — cekDeadlineHarian()
            // return int sehingga bisa ditampilkan di output command
            $jumlah = NotificationService::cekDeadlineHarian();
            $this->info("Selesai. {$jumlah} notifikasi baru dikirim.");
        } catch (\Exception $e) {
            $this->error('Gagal mengirim notifikasi: ' . $e->getMessage());
        }
    }
}
