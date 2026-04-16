<?php

namespace App\Http\Controllers\Siswa;

// FILE: app/Http/Controllers/Siswa/DashboardController.php

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard siswa yang sedang login.
     */
    public function index()
    {
        $user = Auth::user();

        $stats = [
            // ✅ FIX: hitung 'terlambat' juga sebagai sedang dipinjam
            'sedang_dipinjam' => Transaction::where('user_id', $user->id)
                                    ->whereIn('status', ['dipinjam', 'terlambat'])
                                    ->count(),
            'total_pinjam'    => Transaction::where('user_id', $user->id)->count(),
            // ✅ FIX: cukup cek status 'terlambat', tidak perlu query tanggal manual
            'terlambat'       => Transaction::where('user_id', $user->id)
                                    ->where('status', 'terlambat')
                                    ->count(),
        ];

        // Buku yang sedang dipinjam siswa ini (termasuk yang terlambat)
        $pinjaman_aktif = Transaction::with('book')
            ->where('user_id', $user->id)
            ->whereIn('status', ['dipinjam', 'terlambat']) // ✅ FIX: include terlambat
            ->latest()
            ->get();

        // ✅ FIX: eager load category agar tidak N+1
        $buku_tersedia = Book::with('category')
            ->where('stok', '>', 0)
            ->latest()
            ->take(6)
            ->get();

        return view('siswa.dashboard', compact('stats', 'pinjaman_aktif', 'buku_tersedia'));
    }

    /**
     * Katalog semua buku + pencarian (untuk siswa).
     */
    public function katalog(Request $request)
    {
        // ✅ FIX: eager load category agar tidak N+1 query
        $query = Book::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul_buku', 'like', "%{$search}%")
                  ->orWhere('penulis',   'like', "%{$search}%")
                  ->orWhere('penerbit',  'like', "%{$search}%")
                  // ✅ FIX: hapus orWhere('kategori') — kolom sudah dihapus
                  // Ganti dengan search via relasi category
                  ->orWhereHas('category', function ($cat) use ($search) {
                      $cat->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        // ✅ FIX: filter pakai category_id, bukan string 'kategori' lama
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // ✅ FIX: ganti parameter 'tersedia' → 'stok' sesuai view
        if ($request->filled('stok') && $request->stok === 'tersedia') {
            $query->where('stok', '>', 0);
        } elseif ($request->filled('stok') && $request->stok === 'habis') {
            $query->where('stok', 0);
        }

        $buku = $query->latest()->paginate(12)->withQueryString();

        // ✅ FIX: ambil dari tabel categories, bukan distinct dari kolom lama
        $kategori = Category::orderBy('nama')->get();

        return view('siswa.katalog', compact('buku', 'kategori'));
    }
}
