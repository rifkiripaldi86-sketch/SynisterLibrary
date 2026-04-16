<?php

namespace App\Console;

// FILE: app/Console/Kernel.php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Daftarkan semua Artisan commands custom.
     */
    protected $commands = [
        Commands\CekDeadlineNotifikasi::class,
        Commands\CekStokMenipis::class,  // ✅ tambah command stok menipis
    ];

    /**
     * Jadwalkan tasks otomatis.
     * Agar berjalan, tambahkan cron job di server:
     *   * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
     *
     * Di localhost (XAMPP), jalankan manual:
     *   php artisan schedule:work
     */
    protected function schedule(Schedule $schedule): void
    {
        // Notifikasi jatuh tempo & keterlambatan: setiap hari pukul 07.00
        $schedule->command('notif:cek-deadline')->dailyAt('12:00');

        // Notifikasi stok buku menipis (minimal stok 2): setiap hari pukul 08.00
        $schedule->command('notif:cek-stok --min=2')->dailyAt('12:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
