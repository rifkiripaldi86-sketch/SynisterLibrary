<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use Illuminate\Console\Command;

class CekStokMenipis extends Command
{
    protected $signature   = 'notif:cek-stok {--min=2 : Batas minimal stok}';
    protected $description = 'Cek buku dengan stok menipis dan kirim notifikasi ke admin';

    public function handle(): void
    {
        $batas = (int) $this->option('min');
        $this->info("Mengecek buku dengan stok <= {$batas}...");

        try {
            $jumlah = NotificationService::cekStokMenipis($batas);
            $this->info("Selesai. {$jumlah} notifikasi stok menipis dikirim ke admin.");
        } catch (\Exception $e) {
            $this->error('Gagal: ' . $e->getMessage());
        }
    }
}
