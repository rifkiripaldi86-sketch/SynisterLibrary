<?php

namespace App\Http\Controllers\Admin;

// FILE: app/Http/Controllers/Admin/MemberController.php

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class MemberController extends Controller
{
    /**
     * Daftar semua anggota (siswa) + pencarian.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'siswa');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name',     'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('no_induk', 'like', "%{$search}%")
                  ->orWhere('kelas',    'like', "%{$search}%");
            });
        }

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        $anggota       = $query->withCount('transactions')->latest()->paginate(10)->withQueryString();
        $daftar_kelas  = User::where('role', 'siswa')->distinct()->pluck('kelas')->filter()->sort()->values();

        return view('admin.anggota.index', compact('anggota', 'daftar_kelas'));
    }

    /**
     * Form tambah anggota baru (oleh admin).
     */
    public function create()
    {
        return view('admin.anggota.create');
    }

    /**
     * Simpan anggota baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'no_induk' => 'required|string|max:20|unique:users,no_induk',
            'kelas'    => 'required|string|max:30',
            'password' => ['required', Password::min(6)],
        ], [
            'name.required'     => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username sudah digunakan.',
            'no_induk.required' => 'NIS wajib diisi.',
            'no_induk.unique'   => 'NIS sudah terdaftar.',
            'kelas.required'    => 'Kelas wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        User::create([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'no_induk' => $validated['no_induk'],
            'kelas'    => $validated['kelas'],
            'password' => Hash::make($validated['password']),
            'role'     => 'siswa',
        ]);

        return redirect()->route('admin.anggota.index')
            ->with('success', 'Anggota "' . $validated['name'] . '" berhasil ditambahkan!');
    }

    /**
     * Detail anggota + riwayat peminjaman.
     */
    public function show(User $anggotum)
    {
        // Laravel pakai "anggotum" karena route resource: /anggota/{anggotum}
        $anggotum->load(['transactions.book']);
        $riwayat = $anggotum->transactions()->with('book')->latest()->paginate(10);
        return view('admin.anggota.show', compact('anggotum', 'riwayat'));
    }

    /**
     * Form edit anggota.
     */
    public function edit(User $anggotum)
    {
        return view('admin.anggota.edit', compact('anggotum'));
    }

    /**
     * Simpan perubahan data anggota.
     */
    public function update(Request $request, User $anggotum)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $anggotum->id,
            'no_induk' => 'required|string|max:20|unique:users,no_induk,' . $anggotum->id,
            'kelas'    => 'required|string|max:30',
            'password' => ['nullable', Password::min(6)],
        ]);

        $dataUpdate = [
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'no_induk' => $validated['no_induk'],
            'kelas'    => $validated['kelas'],
        ];

        // Hanya update password jika diisi
        if (!empty($validated['password'])) {
            $dataUpdate['password'] = Hash::make($validated['password']);
        }

        $anggotum->update($dataUpdate);

        return redirect()->route('admin.anggota.index')
            ->with('success', 'Data anggota "' . $anggotum->name . '" berhasil diperbarui!');
    }

    /**
     * Hapus anggota.
     */
    public function destroy(User $anggotum)
    {
        // Cegah hapus anggota yang masih punya pinjaman aktif
        if ($anggotum->activeBorrows()->exists()) {
            return back()->with('error', 'Anggota ini masih memiliki pinjaman aktif dan tidak bisa dihapus.');
        }

        $nama = $anggotum->name;
        $anggotum->delete();

        return redirect()->route('admin.anggota.index')
            ->with('success', 'Anggota "' . $nama . '" berhasil dihapus.');
    }
}