<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    /**
     * Tampilkan form pilih buku (dengan checkbox multi-pilih).
     */
    public function createPeminjaman(Request $request)
    {
        $query = Book::with('category')->where('stok', '>', 0);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul_buku', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($cat) use ($search) {
                      $cat->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        // ID buku yang sedang dipinjam (aktif) oleh siswa ini
        $sedang_dipinjam = Transaction::where('user_id', Auth::id())
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->pluck('book_id')
            ->toArray();

        $buku = $query->latest()->paginate(12)->withQueryString();

        // Hitung sisa kuota pinjaman aktif
        $jumlahPinjamanAktif = Transaction::where('user_id', Auth::id())
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->count();
        $sisa_kuota = max(0, 3 - $jumlahPinjamanAktif);

        return view('siswa.peminjaman.create', compact('buku', 'sedang_dipinjam', 'sisa_kuota'));
    }

    /**
     * Proses peminjaman beberapa buku sekaligus.
     */
    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'book_ids' => 'required|array|min:1',
            'book_ids.*' => 'exists:books,id',
        ], [
            'book_ids.required' => 'Pilih minimal 1 buku.',
            'book_ids.min' => 'Pilih minimal 1 buku.',
        ]);

        $user = Auth::user();
        $bookIds = $request->book_ids;

        // Hitung pinjaman aktif saat ini (belum termasuk yang akan dipinjam)
        $pinjamanAktifSaatIni = Transaction::where('user_id', $user->id)
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->count();

        $jumlahBukuBaru = count($bookIds);
        $totalSetelah = $pinjamanAktifSaatIni + $jumlahBukuBaru;

        if ($totalSetelah > 3) {
            return back()->with('error', "Kamu sudah memiliki {$pinjamanAktifSaatIni} pinjaman aktif. Maksimal 3 buku, tidak bisa meminjam {$jumlahBukuBaru} buku sekaligus.");
        }

        $transaksiTersimpan = [];
        $errors = [];

        foreach ($bookIds as $bookId) {
            $buku = Book::findOrFail($bookId);

            // Cek stok
            if (!$buku->isAvailable()) {
                $errors[] = "Buku '{$buku->judul_buku}' sedang habis stok.";
                continue;
            }

            // Cek apakah sudah meminjam buku yang sama
            $sudahPinjam = Transaction::where('user_id', $user->id)
                ->where('book_id', $bookId)
                ->whereIn('status', ['dipinjam', 'terlambat'])
                ->exists();

            if ($sudahPinjam) {
                $errors[] = "Kamu sudah meminjam buku '{$buku->judul_buku}' dan belum dikembalikan.";
                continue;
            }

            // Buat transaksi
            $tanggalPinjam = Carbon::today();
            $tanggalKembali = $tanggalPinjam->copy()->addDays(7);

            $transaksi = Transaction::create([
                'user_id'                 => $user->id,
                'book_id'                 => $buku->id,
                'tanggal_pinjam'          => $tanggalPinjam,
                'tanggal_kembali_rencana' => $tanggalKembali,
                'status'                  => 'dipinjam',
            ]);

            $buku->decrementStock();
            $transaksiTersimpan[] = $transaksi;
        }

        if (count($transaksiTersimpan) === 0) {
            return back()->with('error', 'Tidak ada buku yang berhasil dipinjam. ' . implode(' ', $errors));
        }

        $pesan = count($transaksiTersimpan) . ' buku berhasil dipinjam. ';
        if (!empty($errors)) {
            $pesan .= 'Namun ada yang gagal: ' . implode(' ', $errors);
        }

        return redirect()->route('siswa.dashboard')->with('success', $pesan);
    }

    /**
     * Tampilkan daftar buku yang sedang dipinjam siswa.
     */
    public function createPengembalian()
    {
        $pinjaman = Transaction::with('book')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->latest()
            ->get()
            ->map(function ($trx) {
                $trx->denda_preview = $trx->hitungDenda();
                $trx->is_terlambat  = $trx->isTerlambat();
                return $trx;
            });

        return view('siswa.pengembalian.create', compact('pinjaman'));
    }

    /**
     * Proses konfirmasi pengembalian buku.
     */
    public function storePengembalian(Transaction $transaksi)
    {
        if ($transaksi->user_id !== Auth::id()) {
            abort(403);
        }

        if ($transaksi->status === 'dikembalikan') {
            return back()->with('error', 'Buku ini sudah dikembalikan.');
        }

        $transaksi->prosesPengembalian();

        $pesan = 'Buku "' . $transaksi->book->judul_buku . '" berhasil dikembalikan. Terima kasih!';
        if ($transaksi->denda > 0) {
            $pesan .= ' Ada denda keterlambatan sebesar Rp ' . number_format($transaksi->denda, 0, ',', '.') . '. Harap bayarkan ke petugas perpustakaan.';
        }

        return redirect()->route('siswa.dashboard')->with('success', $pesan);
    }

    /**
     * Riwayat peminjaman.
     */
    public function riwayat()
    {
        $riwayat = Transaction::with('book')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('siswa.riwayat', compact('riwayat'));
    }
}
