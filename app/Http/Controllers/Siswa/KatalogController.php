<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    /**
     * Menampilkan daftar buku dengan filter dan pencarian.
     */
    public function index(Request $request)
    {
        $query = Book::with('category')->where('stok', '>', 0); // hanya tampilkan yang tersedia? Bisa diubah

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul_buku', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%")
                  ->orWhere('penerbit', 'like', "%{$search}%");
            });
        }

        // Filter kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter stok (tersedia/habis)
        if ($request->filled('stok')) {
            if ($request->stok === 'tersedia') {
                $query->where('stok', '>', 0);
            } elseif ($request->stok === 'habis') {
                $query->where('stok', 0);
            }
        }

        $buku = $query->latest()->paginate(12)->withQueryString();
        $kategori = Category::orderBy('nama')->get();

        return view('siswa.katalog', compact('buku', 'kategori'));
    }

    /**
     * Menampilkan detail buku.
     */
    public function show(Book $buku)
    {
        $buku->load('category');
        return view('siswa.buku.show', compact('buku'));
    }
}