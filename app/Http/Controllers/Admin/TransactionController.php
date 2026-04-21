<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Daftar semua transaksi + filter status & pencarian.
     */
    public function index(Request $request)
    {
        Transaction::where('status', 'dipinjam')
            ->whereDate('tanggal_kembali_rencana', '<', today())
            ->update(['status' => 'terlambat']);

        $query = Transaction::with(['user', 'book']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")
                                                   ->orWhere('no_induk', 'like', "%{$search}%"))
                  ->orWhereHas('book', fn($b) => $b->where('judul_buku', 'like', "%{$search}%"));
            });
        }

        $transaksi = $query->latest()->paginate(10)->withQueryString();

        return view('admin.transaksi.index', compact('transaksi'));
    }

    /**
     * Form catat peminjaman baru.
     */
    public function create(Request $request)
    {
        $anggota = User::where('role', 'siswa')->orderBy('name')->get();

        $bookQuery = Book::where('stok', '>', 0)->orderBy('judul_buku');

        if ($request->filled('search')) {
            $search = $request->search;
            $bookQuery->where(function ($q) use ($search) {
                $q->where('judul_buku', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%")
                  ->orWhere('id', $search);
            });
        }

        $buku = $bookQuery->get();

        return view('admin.transaksi.create', compact('anggota', 'buku'));
    }

    /**
     * Simpan peminjaman.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'                 => 'required|exists:users,id',
            'book_ids'                => 'required|array|min:1',
            'book_ids.*'              => 'exists:books,id',
            'tanggal_pinjam'          => 'required|date',
            'durasi'                  => 'required|in:1,3,7',
            'catatan'                 => 'nullable|string|max:500',
        ], [
            'user_id.required'                 => 'Anggota wajib dipilih.',
            'book_ids.required'                => 'Pilih minimal 1 buku.',
            'book_ids.*.exists'                => 'Buku yang dipilih tidak valid.',
            'tanggal_pinjam.required'          => 'Tanggal pinjam wajib diisi.',
            'durasi.required'                  => 'Durasi peminjaman wajib dipilih.',
            'durasi.in'                        => 'Durasi tidak valid.',
        ]);

        $userId = $validated['user_id'];
        $bookIds = $validated['book_ids'];

        // CEK APAKAH USER MEMILIKI BUKU TERLAMBAT
        $user = User::find($userId);
        if ($user && $user->hasOverdueLoan()) {
            return back()->with('error', 'Anggota ini memiliki buku yang terlambat dan belum dikembalikan. Tidak dapat meminjamkan buku baru sampai buku tersebut dikembalikan.');
        }

        $pinjamanAktifSaatIni = Transaction::where('user_id', $userId)
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->count();

        $jumlahBukuBaru = count($bookIds);
        $totalSetelah = $pinjamanAktifSaatIni + $jumlahBukuBaru;

        if ($totalSetelah > 3) {
            return back()->with('error', "Anggota ini sudah memiliki {$pinjamanAktifSaatIni} pinjaman aktif. Maksimal 3 buku, tidak bisa meminjam {$jumlahBukuBaru} buku sekaligus.");
        }

        $tanggalPinjam = Carbon::parse($validated['tanggal_pinjam']);
        $tanggalKembali = $tanggalPinjam->copy()->addDays($validated['durasi']);

        $transaksiTersimpan = [];
        $errors = [];

        foreach ($bookIds as $bookId) {
            $buku = Book::findOrFail($bookId);

            if (!$buku->isAvailable()) {
                $errors[] = "Stok buku '{$buku->judul_buku}' habis!";
                continue;
            }

            $sudahPinjam = Transaction::where('user_id', $userId)
                ->where('book_id', $bookId)
                ->whereIn('status', ['dipinjam', 'terlambat'])
                ->exists();

            if ($sudahPinjam) {
                $errors[] = "Anggota ini sudah meminjam buku '{$buku->judul_buku}' dan belum dikembalikan.";
                continue;
            }

            $transaksi = Transaction::create([
                'user_id'                 => $userId,
                'book_id'                 => $bookId,
                'tanggal_pinjam'          => $tanggalPinjam,
                'tanggal_kembali_rencana' => $tanggalKembali,
                'catatan'                 => $validated['catatan'] ?? null,
                'status'                  => 'dipinjam',
            ]);

            $buku->decrementStock();
            $transaksiTersimpan[] = $transaksi;
            NotificationService::berhasilPinjam($transaksi);
        }

        if (count($transaksiTersimpan) === 0) {
            return back()->with('error', 'Tidak ada buku yang berhasil dipinjam. ' . implode(' ', $errors));
        }

        $pesan = count($transaksiTersimpan) . ' buku berhasil dipinjamkan.';
        if (!empty($errors)) {
            $pesan .= ' Namun ada yang gagal: ' . implode(' ', $errors);
        }

        return redirect()->route('admin.transaksi.index')->with('success', $pesan);
    }

    /**
     * AJAX: Cek apakah anggota memiliki buku terlambat.
     */
    public function cekOverdue(Request $request)
    {
        $userId = $request->user_id;
        $user = User::find($userId);
        if ($user) {
            return response()->json(['has_overdue' => $user->hasOverdueLoan()]);
        }
        return response()->json(['has_overdue' => false]);
    }

    /**
     * Detail satu transaksi.
     */
    public function show(Transaction $transaksi)
    {
        $transaksi->load(['user', 'book']);
        return view('admin.transaksi.show', compact('transaksi'));
    }

    /**
     * Form edit transaksi.
     */
    public function edit(Transaction $transaksi)
    {
        $anggota = User::where('role', 'siswa')->orderBy('name')->get();
        $buku    = Book::orderBy('judul_buku')->get();
        return view('admin.transaksi.edit', compact('transaksi', 'anggota', 'buku'));
    }

    /**
     * Simpan perubahan transaksi.
     */
    public function update(Request $request, Transaction $transaksi)
    {
        $validated = $request->validate([
            'tanggal_kembali_rencana' => 'required|date|after:tanggal_pinjam',
            'catatan'                 => 'nullable|string|max:500',
        ]);

        $transaksi->update($validated);

        return redirect()->route('admin.transaksi.index')
            ->with('success', 'Data transaksi berhasil diperbarui!');
    }

    /**
     * Hapus transaksi (hanya yang sudah dikembalikan).
     */
    public function destroy(Transaction $transaksi)
    {
        if ($transaksi->status !== 'dikembalikan') {
            return back()->with('error', 'Transaksi yang masih aktif tidak bisa dihapus.');
        }

        $transaksi->delete();

        return redirect()->route('admin.transaksi.index')
            ->with('success', 'Data transaksi berhasil dihapus.');
    }

    /**
     * Proses pengembalian buku.
     */
    public function prosesPengembalian(Transaction $transaksi)
    {
        if ($transaksi->status === 'dikembalikan') {
            return back()->with('error', 'Buku ini sudah dikembalikan sebelumnya.');
        }

        $transaksi->prosesPengembalian();
        NotificationService::berhasilKembali($transaksi);

        $pesan = 'Buku "' . $transaksi->book->judul_buku . '" berhasil dikembalikan.';

        if ($transaksi->denda > 0) {
            $pesan .= ' Denda keterlambatan: Rp ' . number_format($transaksi->denda, 0, ',', '.');
        }

        return redirect()->route('admin.transaksi.index')->with('success', $pesan);
    }
}
