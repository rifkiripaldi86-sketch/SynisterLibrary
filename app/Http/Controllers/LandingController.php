<?php

namespace App\Http\Controllers;

// FILE: app/Http/Controllers/LandingController.php

use App\Models\Book;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Tampilkan landing page Synister Library.
     * Menampilkan statistik dasar dan buku terbaru untuk pengunjung.
     */
    public function index()
    {
        // Ambil beberapa data untuk ditampilkan di landing page
        $totalBuku     = Book::count();
        $bukuTersedia  = Book::where('stok', '>', 0)->count();
        $bukuTerbaru   = Book::latest()->take(6)->get();

        return view('landing', compact(
            'totalBuku',
            'bukuTersedia',
            'bukuTerbaru'
        ));
    }
}