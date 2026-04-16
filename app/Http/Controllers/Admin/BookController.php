<?php

namespace App\Http\Controllers\Admin;

// FILE: app/Http/Controllers/Admin/BookController.php

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul_buku', 'like', "%{$search}%")
                  ->orWhere('penulis',   'like', "%{$search}%")
                  ->orWhere('penerbit',  'like', "%{$search}%")
                  ->orWhere('isbn',      'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $buku      = $query->latest()->paginate(10)->withQueryString();
        $kategori  = Category::orderBy('nama')->get();

        return view('admin.buku.index', compact('buku', 'kategori'));
    }

    public function create()
    {
        $kategori = Category::orderBy('nama')->get();
        return view('admin.buku.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_buku'   => 'required|string|max:200',
            'penulis'      => 'required|string|max:100',
            'penerbit'     => 'required|string|max:100',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'isbn'         => 'nullable|string|max:20|unique:books,isbn',
            'category_id'  => 'nullable|exists:categories,id',
            'stok'         => 'required|integer|min:0',
            'deskripsi'    => 'nullable|string',
            'cover_image'  => 'nullable|file|mimes:jpg,jpeg,png,webp,bmp|max:2048', // diperbaiki
        ], [
            'judul_buku.required'   => 'Judul buku wajib diisi.',
            'penulis.required'      => 'Penulis wajib diisi.',
            'penerbit.required'     => 'Penerbit wajib diisi.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'stok.required'         => 'Stok wajib diisi.',
            'isbn.unique'           => 'ISBN sudah terdaftar.',
            'cover_image.mimes'     => 'Cover harus berupa gambar (jpg, jpeg, png, webp, bmp).',
            'cover_image.max'       => 'Ukuran cover maksimal 2MB.',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')
                ->store('covers', 'public');
        }

        Book::create($validated);

        return redirect()->route('admin.buku.index')
            ->with('success', 'Buku "' . $validated['judul_buku'] . '" berhasil ditambahkan!');
    }

    public function show(Book $buku)
    {
        $buku->load(['category', 'transactions.user']);
        $riwayat = $buku->transactions()->with('user')->latest()->paginate(10);
        return view('admin.buku.show', compact('buku', 'riwayat'));
    }

    public function edit(Book $buku)
    {
        $kategori = Category::orderBy('nama')->get();
        return view('admin.buku.edit', compact('buku', 'kategori'));
    }

    public function update(Request $request, Book $buku)
    {
        $validated = $request->validate([
            'judul_buku'   => 'required|string|max:200',
            'penulis'      => 'required|string|max:100',
            'penerbit'     => 'required|string|max:100',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'isbn'         => 'nullable|string|max:20|unique:books,isbn,' . $buku->id,
            'category_id'  => 'nullable|exists:categories,id',
            'stok'         => 'required|integer|min:0',
            'deskripsi'    => 'nullable|string',
            'cover_image'  => 'nullable|file|mimes:jpg,jpeg,png,webp,bmp|max:2048', // diperbaiki
        ], [
            'cover_image.mimes' => 'Cover harus berupa gambar (jpg, jpeg, png, webp, bmp).',
            'cover_image.max'   => 'Ukuran cover maksimal 2MB.',
        ]);

        if ($request->hasFile('cover_image')) {
            // Hapus cover lama
            if ($buku->cover_image) {
                Storage::disk('public')->delete($buku->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')
                ->store('covers', 'public');
        }

        $buku->update($validated);

        return redirect()->route('admin.buku.index')
            ->with('success', 'Data buku "' . $buku->judul_buku . '" berhasil diperbarui!');
    }

    public function destroy(Book $buku)
    {
        if ($buku->activeTransactions()->exists()) {
            return back()->with('error', 'Buku ini masih dipinjam dan tidak bisa dihapus.');
        }

        if ($buku->cover_image) {
            Storage::disk('public')->delete($buku->cover_image);
        }

        $judul = $buku->judul_buku;
        $buku->delete();

        return redirect()->route('admin.buku.index')
            ->with('success', 'Buku "' . $judul . '" berhasil dihapus.');
    }
}