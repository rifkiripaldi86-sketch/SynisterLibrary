<?php

namespace App\Http\Controllers;

// FILE: app/Http/Controllers/NotificationController.php

use App\Models\AppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Daftar semua notifikasi milik user yang login.
     *
     * FIX #1: Mark read DULU sebelum fetch, bukan sesudah.
     * Sebelumnya: fetch → mark read → view masih tampilkan is_read=false
     * Sesudahnya: mark read → fetch → view sudah tampilkan is_read=true
     */
    public function index()
    {
        // ✅ LANGKAH 1: Mark semua sebagai dibaca DULU
        AppNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        // ✅ LANGKAH 2: Baru fetch — semua sudah is_read=true
        $notifikasi = AppNotification::where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('notifikasi.index', compact('notifikasi'));
    }

    /**
     * Tandai satu notifikasi sebagai dibaca.
     *
     * FIX #6: Ganti return back() → redirect ke route yang pasti ada,
     * agar tidak balik ke halaman kosong jika user buka via direct link.
     */
    public function markRead(AppNotification $notifikasi)
    {
        // Pastikan notif ini milik user yang login
        if ($notifikasi->user_id !== Auth::id()) {
            abort(403);
        }

        $notifikasi->markAsRead();

        // ✅ Redirect ke halaman notifikasi, bukan back()
        return redirect()->route('notifikasi.index');
    }

    /**
     * Hapus semua notifikasi milik user yang login.
     */
    public function hapusSemua()
    {
        AppNotification::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Semua notifikasi telah dihapus.');
    }

    /**
     * Jumlah notif belum dibaca — untuk badge di topbar (bisa dipanggil via AJAX).
     */
    public function unreadCount()
    {
        $count = AppNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
