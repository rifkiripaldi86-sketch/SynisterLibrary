<?php

namespace App\Http\Controllers\Admin;

// FILE: app/Http/Controllers/Admin/ReportController.php

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Range tanggal — default bulan ini
        $dari  = $request->filled('dari')
                 ? Carbon::parse($request->dari)->startOfDay()
                 : Carbon::now()->startOfMonth();
        $sampai = $request->filled('sampai')
                 ? Carbon::parse($request->sampai)->endOfDay()
                 : Carbon::now()->endOfDay();

        // ── Ringkasan umum ────────────────────────────────────
        $ringkasan = [
            'total_transaksi'   => Transaction::whereBetween('created_at', [$dari, $sampai])->count(),
            'total_pinjam'      => Transaction::whereBetween('tanggal_pinjam', [$dari, $sampai])->count(),
            'total_kembali'     => Transaction::whereBetween('tanggal_kembali_aktual', [$dari, $sampai])
                                       ->where('status', 'dikembalikan')->count(),
            'total_terlambat'   => Transaction::whereBetween('tanggal_pinjam', [$dari, $sampai])
                                       ->where('status', 'terlambat')->count(),
            'total_denda'       => Transaction::whereBetween('tanggal_kembali_aktual', [$dari, $sampai])
                                       ->sum('denda'),
            'aktif_dipinjam'    => Transaction::whereIn('status', ['dipinjam', 'terlambat'])->count(),
        ];

        // ── Buku terpopuler ───────────────────────────────────
        $bukuPopuler = Book::withCount(['transactions' => function ($q) use ($dari, $sampai) {
                $q->whereBetween('tanggal_pinjam', [$dari, $sampai]);
            }])
            ->orderByDesc('transactions_count')
            ->take(10)
            ->get();

        // ── Anggota teraktif ──────────────────────────────────
        $anggotaAktif = User::where('role', 'siswa')
            ->withCount(['transactions' => function ($q) use ($dari, $sampai) {
                $q->whereBetween('tanggal_pinjam', [$dari, $sampai]);
            }])
            ->orderByDesc('transactions_count')
            ->take(10)
            ->get();

        // ── Pinjaman per bulan (12 bulan terakhir) ────────────
        $perBulan = collect();
        for ($i = 11; $i >= 0; $i--) {
            $bulan  = Carbon::now()->subMonths($i);
            $jumlah = Transaction::whereYear('tanggal_pinjam', $bulan->year)
                          ->whereMonth('tanggal_pinjam', $bulan->month)
                          ->count();
            $perBulan->push([
                'label'  => $bulan->locale('id')->isoFormat('MMM YY'),
                'jumlah' => $jumlah,
            ]);
        }

        // ── Distribusi per kategori ───────────────────────────
        $perKategori = Category::withCount(['books as total_pinjam' => function ($q) use ($dari, $sampai) {
                $q->whereHas('transactions', function ($t) use ($dari, $sampai) {
                    $t->whereBetween('tanggal_pinjam', [$dari, $sampai]);
                });
            }])
            ->having('total_pinjam', '>', 0)
            ->orderByDesc('total_pinjam')
            ->get();

        // ── Transaksi terlambat aktif ─────────────────────────
        $terlambatAktif = Transaction::with(['user', 'book'])
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->whereDate('tanggal_kembali_rencana', '<', today())
            ->orderBy('tanggal_kembali_rencana')
            ->get()
            ->map(function ($trx) {
                $trx->hari_terlambat = today()->diffInDays($trx->tanggal_kembali_rencana);
                $trx->denda_akrual   = $trx->hari_terlambat * 1000;
                return $trx;
            });

        return view('admin.laporan.index', compact(
            'ringkasan',
            'bukuPopuler',
            'anggotaAktif',
            'perBulan',
            'perKategori',
            'terlambatAktif',
            'dari',
            'sampai'
        ));
    }
}
