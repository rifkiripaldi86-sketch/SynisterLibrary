<?php

namespace App\Http\Controllers\Admin;

// FILE: app/Http/Controllers/Admin/CategoryController.php

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('books');

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $kategori = $query->latest()->paginate(15)->withQueryString();

        return view('admin.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:60|unique:categories,nama',
            'deskripsi' => 'nullable|string|max:300',
            'warna'     => 'required|string|size:7|starts_with:#',
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique'   => 'Nama kategori sudah ada.',
            'warna.required'=> 'Pilih warna untuk kategori.',
        ]);

        $validated['slug'] = Str::slug($validated['nama']);

        Category::create($validated);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori "' . $validated['nama'] . '" berhasil ditambahkan!');
    }

    public function edit(Category $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Category $kategori)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:60|unique:categories,nama,' . $kategori->id,
            'deskripsi' => 'nullable|string|max:300',
            'warna'     => 'required|string|size:7|starts_with:#',
        ]);

        $kategori->update($validated);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori "' . $kategori->nama . '" berhasil diperbarui!');
    }

    public function destroy(Category $kategori)
    {
        if ($kategori->books()->count() > 0) {
            return back()->with('error',
                'Kategori "' . $kategori->nama . '" tidak bisa dihapus karena masih digunakan oleh '
                . $kategori->books()->count() . ' buku.');
        }

        $nama = $kategori->nama;
        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori "' . $nama . '" berhasil dihapus.');
    }
}
