<?php

// FILE: routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\DashboardController  as AdminDashboardController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Siswa\DashboardController  as SiswaDashboardController;
use App\Http\Controllers\Siswa\PeminjamanController;
use App\Http\Controllers\Siswa\KatalogController;

// ─────────────────────────────────────────────
// 1. Landing
// ─────────────────────────────────────────────
Route::get('/', [LandingController::class, 'index'])->name('home');

// ─────────────────────────────────────────────
// 2. Auth
// ─────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─────────────────────────────────────────────
// 3. Notifikasi (semua user login)
// ─────────────────────────────────────────────
Route::middleware('auth')->prefix('notifikasi')->name('notifikasi.')->group(function () {
    Route::get('/',                   [NotificationController::class, 'index'])->name('index');
    Route::post('/{notifikasi}/baca', [NotificationController::class, 'markRead'])->name('baca');
    Route::delete('/hapus-semua',     [NotificationController::class, 'hapusSemua'])->name('hapus');
    Route::get('/unread-count',       [NotificationController::class, 'unreadCount'])->name('unread-count');
});

// ─────────────────────────────────────────────
// 4. Admin
// ─────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/search',    [AdminDashboardController::class, 'search'])->name('search');

    // ✅ FIX #2: Route custom 'kembalikan' didaftarkan SEBELUM Route::resource()
    // agar tidak terjadi konflik parameter binding dengan route resource bawaan Laravel.
    // Jika didaftarkan sesudah, Laravel bisa salah memetakan {transaksi}/kembalikan
    // sebagai parameter resource biasa.
    Route::post('/transaksi/{transaksi}/kembalikan',
        [AdminTransactionController::class, 'prosesPengembalian']
    )->name('transaksi.kembalikan');

    // Resource routes — didaftarkan SETELAH route custom
    Route::resource('buku',      BookController::class);
    Route::resource('anggota',   MemberController::class);
    Route::resource('transaksi', AdminTransactionController::class);
    Route::resource('kategori',  CategoryController::class);

    Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
});

// ─────────────────────────────────────────────
// 5. Siswa
// ─────────────────────────────────────────────
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {

    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');

    // Katalog & detail buku
    Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog');
    Route::get('/buku/{buku}', [KatalogController::class, 'show'])->name('buku.show');

    // Peminjaman
    Route::get('/peminjaman',  [PeminjamanController::class, 'createPeminjaman'])->name('peminjaman.create');
    Route::post('/peminjaman', [PeminjamanController::class, 'storePeminjaman'])->name('peminjaman.store');

    // Pengembalian (jika diaktifkan nanti)
    // Route::get('/pengembalian', [PeminjamanController::class, 'createPengembalian'])->name('pengembalian.create');
    // Route::post('/pengembalian/{transaksi}', [PeminjamanController::class, 'storePengembalian'])->name('pengembalian.store');

    Route::get('/riwayat', [PeminjamanController::class, 'riwayat'])->name('riwayat');
});