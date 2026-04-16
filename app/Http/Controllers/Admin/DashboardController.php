<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard admin dengan statistik ringkasan.
     */
    public function index()
    {
        // ✅ Update status terlambat terlebih dahulu agar data statistik akurat
        Transaction::where('status', 'dipinjam')
            ->whereDate('tanggal_kembali_rencana', '<', Carbon::today())
            ->update(['status' => 'terlambat']);

        $stats = [
            'total_buku'        => Book::count(),
            'total_anggota'     => User::where('role', 'siswa')->count(),
            'sedang_dipinjam'   => Transaction::where('status', 'dipinjam')->count(),
            'dikembalikan'      => Transaction::where('status', 'dikembalikan')->count(),
            'terlambat'         => Transaction::where('status', 'terlambat')->count(), // sekarang akurat
        ];

        $transaksi_terbaru = Transaction::with(['user', 'book'])
            ->latest()
            ->take(8)
            ->get();

        $buku_terpopuler = Book::withCount('transactions')
            ->orderByDesc('transactions_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'transaksi_terbaru', 'buku_terpopuler'));
    }

    /**
     * Pencarian global untuk admin.
     */
    public function search(\Illuminate\Http\Request $request)
    {
        $query = $request->get('q');

        $buku = Book::where('judul_buku', 'like', "%{$query}%")
            ->orWhere('penulis', 'like', "%{$query}%")
            ->orWhere('isbn', 'like', "%{$query}%")
            ->get();

        $anggota = User::where('role', 'siswa')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('no_induk', 'like', "%{$query}%")
                  ->orWhere('username', 'like', "%{$query}%");
            })->get();

        return view('admin.search.index', compact('query', 'buku', 'anggota'));
    }
}
