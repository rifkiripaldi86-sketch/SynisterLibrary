<?php

namespace App\Http\Controllers\Admin;

// FILE: app/Http/Controllers/Admin/TransactionController.php

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
        // Update status terlambat
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
     * Form catat peminjaman baru (oleh admin) - bisa pilih banyak buku.
     */
    public function create()
    {
        $anggota = User::where('role', 'siswa')->orderBy('name')->get();
        $buku    = Book::where('stok', '>', 0)->orderBy('judul_buku')->get();

        return view('admin.transaksi.create', compact('anggota', 'buku'));
    }

    /**
     * Simpan peminjaman untuk 1 atau lebih buku sekaligus.
     * Maksimal total pinjaman aktif (termasuk yang akan dipinjam) = 3 buku.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'                 => 'required|exists:users,id',
            'book_ids'                => 'required|array|min:1',
            'book_ids.*'              => 'exists:books,id',
            'tanggal_pinjam'          => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after:tanggal_pinjam',
            'catatan'                 => 'nullable|string|max:500',
        ], [
            'user_id.required'                 => 'Anggota wajib dipilih.',
            'book_ids.required'                => 'Pilih minimal 1 buku.',
            'book_ids.*.exists'                => 'Buku yang dipilih tidak valid.',
            'tanggal_pinjam.required'          => 'Tanggal pinjam wajib diisi.',
            'tanggal_kembali_rencana.required' => 'Tanggal kembali wajib diisi.',
            'tanggal_kembali_rencana.after'    => 'Tanggal kembali harus setelah tanggal pinjam.',
        ]);

        $userId = $validated['user_id'];
        $bookIds = $validated['book_ids'];

        // Hitung jumlah pinjaman aktif anggota saat ini (belum termasuk yg akan dipinjam)
        $pinjamanAktifSaatIni = Transaction::where('user_id', $userId)
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->count();

        $jumlahBukuBaru = count($bookIds);
        $totalSetelah = $pinjamanAktifSaatIni + $jumlahBukuBaru;

        if ($totalSetelah > 3) {
            return back()->with('error', "Anggota ini sudah memiliki {$pinjamanAktifSaatIni} pinjaman aktif. Maksimal 3 buku, tidak bisa meminjam {$jumlahBukuBaru} buku sekaligus.");
        }

        $transaksiTersimpan = [];
        $errors = [];

        foreach ($bookIds as $bookId) {
            $buku = Book::findOrFail($bookId);

            // Cek stok
            if (!$buku->isAvailable()) {
                $errors[] = "Stok buku '{$buku->judul_buku}' habis!";
                continue;
            }

            // Cek apakah siswa sudah meminjam buku yang sama (belum dikembalikan)
            $sudahPinjam = Transaction::where('user_id', $userId)
                ->where('book_id', $bookId)
                ->whereIn('status', ['dipinjam', 'terlambat'])
                ->exists();

            if ($sudahPinjam) {
                $errors[] = "Anggota ini sudah meminjam buku '{$buku->judul_buku}' dan belum dikembalikan.";
                continue;
            }

            // Buat transaksi
            $transaksi = Transaction::create([
                'user_id'                 => $userId,
                'book_id'                 => $bookId,
                'tanggal_pinjam'          => $validated['tanggal_pinjam'],
                'tanggal_kembali_rencana' => $validated['tanggal_kembali_rencana'],
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
     * Detail satu transaksi.
     */
    public function show(Transaction $transaksi)
    {
        $transaksi->load(['user', 'book']);
        return view('admin.transaksi.show', compact('transaksi'));
    }

    /**
     * Form edit transaksi (untuk koreksi data).
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
     * Proses pengembalian buku — route custom:
     * POST /admin/transaksi/{transaksi}/kembalikan
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
