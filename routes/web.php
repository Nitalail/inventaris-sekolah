<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\RuanganController;
use App\Http\Controllers\Admin\SubBarangController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\User\DashboardUserController;
use App\Http\Controllers\User\PeminjamanController;
use App\Http\Controllers\User\TransaksiController;
use App\Http\Controllers\User\PinjamanSayaController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\NotificationController;


// 🏠 Halaman utama (redirect ke dashboard jika sudah login)
Route::get('/', function () {
    return view('welcome');
});

// 🔐 Auth bawaan Laravel
require __DIR__ . '/auth.php';

// 👤 Dashboard untuk user biasa (non-admin)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// 👤 Pengaturan profile user
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 👮 Rute untuk admin
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        // Dashboard Admin
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // 📦 Barang
        Route::get('barang/count', [BarangController::class, 'getCount'])->name('barang.count');
        Route::resource('barang', BarangController::class);
        Route::get('barang-export', [BarangController::class, 'export'])->name('barang.export');
        Route::get('barang-print', [BarangController::class, 'print'])->name('barang.print');

        // 📦 Sub Barang
        Route::resource('sub-barang', SubBarangController::class);
        Route::get('sub-barang/by-barang/{barangId}', [SubBarangController::class, 'getByBarang'])->name('sub-barang.by-barang');
        Route::get('sub-barang/available/{barangId}', [SubBarangController::class, 'getAvailableByBarang'])->name('sub-barang.available');
        
        // Temporary test route without auth for debugging
        Route::post('sub-barang/test-update/{id}', [SubBarangController::class, 'testUpdate'])->name('sub-barang.test-update')->withoutMiddleware(['auth', 'admin']);



        // 🗂️ Kategori
        Route::get('kategori/count', [KategoriController::class, 'getCount'])->name('kategori.count');
        Route::resource('kategori', KategoriController::class);

        // 🏫 Ruangan
        Route::get('ruangan/count', [RuanganController::class, 'getCount'])->name('ruangan.count');
        Route::resource('ruangan', RuanganController::class);
        Route::get('ruangan/export-pdf', [RuanganController::class, 'exportPDF'])->name('ruangan.exportPDF');

        // 🔄 Transaksi
        Route::resource('transaksi', TransaksiController::class);
        // Route untuk menyimpan transaksi peminjaman
        Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');

        // Route untuk melihat detail transaksi
        Route::get('/transaksi/{transaksi}', [TransaksiController::class, 'show'])->name('transaksi.show');
        Route::get('/transaksi/{id}/detail', [TransaksiController::class, 'getDetail'])->name('transaksi.detail');

        // Route untuk melihat daftar transaksi user
        Route::get('/user/pinjaman-saya', [TransaksiController::class, 'userTransactions'])->name('transaksi.user');

        // Route untuk membatalkan transaksi pending
        Route::delete('/transaksi/{transaksi}/cancel', [TransaksiController::class, 'cancel'])->name('transaksi.cancel');

        // 👥 Pengguna
        Route::resource('pengguna', PenggunaController::class);

        // 📑 Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::post('/laporan/generate', [LaporanController::class, 'generate'])->name('laporan.generate');
        Route::get('/laporan/download/{id}', [LaporanController::class, 'download'])->name('laporan.download');
        Route::post('barang/laporan', [BarangController::class, 'generateInventoryReport'])->name('barang.laporan');

        // 🔍 Search
        Route::prefix('search')->name('search.')->group(function () {
            Route::get('/global', [App\Http\Controllers\Admin\SearchController::class, 'globalSearch'])->name('global');
            Route::get('/quick', [App\Http\Controllers\Admin\SearchController::class, 'quickSearch'])->name('quick');
        });

        // 🔔 Notifications
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::get('/count', [NotificationController::class, 'getUnreadCount'])->name('count');
            Route::get('/stats', [NotificationController::class, 'getStats'])->name('stats');
            Route::post('/test', [NotificationController::class, 'createTestNotification'])->name('test');
            Route::put('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
            Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
            Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        });


        // Dashboard data API route
        Route::get('/dashboard/data', [DashboardController::class, 'getData'])->name('dashboard.data');
    });

// 👥 User view routes (static views) - REMOVED: These routes are now handled by controllers with auth middleware

Route::prefix('user')
    ->middleware('auth')
    ->group(function () {
        Route::get('dashboard', [DashboardUserController::class, 'index'])->name('user.dashboard');
        Route::view('home', 'user.home')->name('user.home');
        Route::view('pinjaman-saya', 'user.pinjaman-saya')->name('user.pinjaman-saya');
        Route::view('riwayat', 'user.riwayat')->name('user.riwayat');
        Route::get('profile', [App\Http\Controllers\User\ProfileController::class, 'index'])->name('user.profile');
        Route::patch('profile', [App\Http\Controllers\User\ProfileController::class, 'update'])->name('user.profile.update');
        Route::put('profile/password', [App\Http\Controllers\User\ProfileController::class, 'updatePassword'])->name('user.profile.password');
        Route::delete('profile', [App\Http\Controllers\User\ProfileController::class, 'destroy'])->name('user.profile.destroy');

        // Route untuk mendapatkan sub barang yang tersedia (untuk user)
        Route::get('sub-barang/available/{barangId}', [SubBarangController::class, 'getAvailableByBarang'])->name('user.sub-barang.available');

        // Transaksi user - commented out until proper controller is created
        // Route::post('transaksi', [UserTransaksiController::class, 'store'])->name('transaksi.store');

        // Pinjam barang - commented out until proper controller is created  
        // Route::post('pinjam/{barang}', [PinjamController::class, 'pinjamBarang'])->name('pinjam.barang');
    });

// Duplicate route removed - handled by resource route in admin group

// Alternative route for user dashboard (for backward compatibility)
Route::middleware('auth')->group(function () {
    Route::get('/user/dashboard-user', [DashboardUserController::class, 'index'])->name('user.dashboard-user');
});

Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
Route::prefix('user')
    ->middleware('auth')
    ->group(function () {
        Route::post('/peminjaman/store', [PeminjamanController::class, 'store'])->name('user.peminjaman.store');
    });
Route::put('/peminjaman/{id}', [PeminjamanController::class, 'update'])->name('peminjaman.update');

Route::post('/user/peminjaman/store', [PeminjamanController::class, 'store'])->name('user.peminjaman.store');

// Route commented out until proper controller is created
// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard-user', [UserController::class, 'index'])->name('dashboard.user');
// });
// Duplicate routes removed - handled by resource routes in admin group

Route::middleware(['auth'])->group(function () {
    // Routes untuk halaman Pinjaman Saya
    Route::prefix('user')->group(function () {
        // Halaman utama pinjaman saya
        Route::get('/pinjaman-saya', [PinjamanSayaController::class, 'index'])->name('user.pinjaman-saya');

        // API Routes untuk AJAX
        Route::prefix('pinjaman-saya')->group(function () {
            // Filter pinjaman
            Route::get('/filter', [PinjamanSayaController::class, 'filter'])->name('user.pinjaman-saya.filter');

            // Detail pinjaman
            Route::get('/detail/{id}', [PinjamanSayaController::class, 'getDetail'])->name('user.pinjaman-saya.detail');

            // Ajukan perpanjangan
            Route::post('/perpanjang', [PinjamanSayaController::class, 'ajukanPerpanjangan'])->name('user.pinjaman-saya.perpanjang');

            // Ajukan pengembalian
            Route::post('/kembalikan', [PinjamanSayaController::class, 'ajukanPengembalian'])->name('user.pinjaman-saya.kembalikan');
        });
    });
});

// Route untuk riwayat peminjaman
Route::middleware(['auth'])->group(function () {
    Route::get('/user/riwayat', [App\Http\Controllers\User\RiwayatController::class, 'index'])->name('user.riwayat');
    Route::get('/user/riwayat/export-pdf', [App\Http\Controllers\User\RiwayatController::class, 'exportPdf'])->name('user.riwayat.export');
    Route::get('/user/riwayat/filter-category', [App\Http\Controllers\User\RiwayatController::class, 'filterByCategory'])->name('user.riwayat.filter.category');
    Route::get('/user/riwayat/filter-month', [App\Http\Controllers\User\RiwayatController::class, 'filterByMonth'])->name('user.riwayat.filter.month');
    Route::get('/user/riwayat/search', [App\Http\Controllers\User\RiwayatController::class, 'search'])->name('user.riwayat.search');
});



Route::middleware('auth')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});
Route::put('/admin/pengguna/{id}', [PenggunaController::class, 'update']);

// Temporary CSRF test routes - remove after testing
Route::get('/test-csrf', function () {
    return view('test-csrf');
});

Route::get('/test-csrf-profile', function () {
    return view('test-csrf-profile');
});

Route::get('/test-modal', function () {
    return view('test-modal');
});

Route::post('/test-csrf-endpoint', function () {
    return response()->json([
        'success' => true,
        'message' => 'CSRF test passed!',
        'token' => csrf_token()
    ]);
});
