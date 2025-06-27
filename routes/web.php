<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\RuanganController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\User\DashboardUserController;
use App\Http\Controllers\User\PeminjamanController;
use App\Http\Controllers\User\TransaksiController;
use App\Http\Controllers\User\PinjamanSayaController;


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
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 📦 Barang
    Route::resource('barang', BarangController::class);
    Route::post('/admin/barang/store', [BarangController::class, 'store'])->name('admin.barang.store');
    Route::get('/admin/barang', [BarangController::class, 'index'])->name('admin.barang');
    Route::put('/admin/barang/{barang}', [BarangController::class, 'update'])->name('admin.barang.update');
    Route::put('/admin/barang/{id}', [BarangController::class, 'update'])->name('admin.barang.update');

    Route::resource('admin/barang', BarangController::class);
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('barang', BarangController::class);
        Route::get('barang-export', [BarangController::class, 'export'])->name('barang.export');
        Route::get('barang-print', [BarangController::class, 'print'])->name('barang.print');
    });

    // 🗂️ Kategori
    Route::resource('kategori', KategoriController::class);
    Route::delete('/admin/kategori/{id}', [KategoriController::class, 'destroy'])->name('admin.kategori.destroy');
    Route::resource('admin/kategori', KategoriController::class);

    // 🏫 Ruangan
    Route::resource('ruangan', RuanganController::class);
    Route::post('/admin/ruangan', [RuanganController::class, 'store'])->name('admin.ruangan.store');
    Route::put('/admin/ruangan/{id}', [RuanganController::class, 'update'])->name('admin.ruangan.update');
    Route::get('/admin/ruangan/export-pdf', [RuanganController::class, 'exportPDF'])->name('admin.ruangan.exportPDF');


    // 🔄 Transaksi
    Route::resource('transaksi', TransaksiController::class);
    // Route untuk menyimpan transaksi peminjaman
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');

    // Route untuk melihat detail transaksi
    Route::get('/transaksi/{transaksi}', [TransaksiController::class, 'show'])->name('transaksi.show');

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
    Route::post('/admin/barang/laporan', [BarangController::class, 'generateInventoryReport'])
    ->name('admin.barang.laporan');
    

    // ⚙️ Pengaturan
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
    
});


// 👥 User view routes (static views)
Route::prefix('user')->group(function () {
    Route::view('/home', 'user.home');
    Route::view('/dashboard-user', 'user.dashboard-user');
    Route::view('/pinjaman-saya', 'user.pinjaman-saya');
    Route::view('/riwayat', 'user.riwayat');
    Route::view('/profile', 'user.profile');
});

Route::prefix('user')->middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardUserController::class, 'index'])->name('dashboard');
    Route::view('home', 'user.home')->name('user.home');
    Route::view('pinjaman-saya', 'user.pinjaman-saya')->name('user.pinjaman-saya');
    Route::view('riwayat', 'user.riwayat')->name('user.riwayat');
    Route::view('profile', 'user.profile')->name('user.profile');

    // Transaksi user
    Route::post('transaksi', [UserTransaksiController::class, 'store'])->name('transaksi.store');

    // Pinjam barang
    Route::post('pinjam/{barang}', [PinjamController::class, 'pinjamBarang'])->name('pinjam.barang');
});

Route::get('/admin/transaksi', [App\Http\Controllers\User\TransaksiController::class, 'index'])->name('admin.transaksi.index');

// Jangan dihapus
Route::get('/user/dashboard-user', [DashboardUserController::class, 'index'])->name('user.dashboard');

Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
Route::prefix('user')->middleware('auth')->group(function () {
    Route::post('/peminjaman/store', [PeminjamanController::class, 'store'])->name('user.peminjaman.store');
});
Route::put('/peminjaman/{id}', [PeminjamanController::class, 'update'])->name('peminjaman.update');

Route::post('/user/peminjaman/store', [PeminjamanController::class, 'store'])->name('user.peminjaman.store');

Route::middleware(['auth'])->group(function () {
Route::get('/dashboard-user', [UserController::class, 'index'])->name('dashboard.user');
});
Route::middleware(['admin'])->group(function () {
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('admin.transaksi.index');
    Route::post('/transaksi/{id}', [TransaksiController::class, 'update'])->name('admin.transaksi.update');
    Route::delete('/admin/transaksi/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
});

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

Route::prefix('admin')->group(function() {
    Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan');
    Route::post('/laporan/generate', [LaporanController::class, 'generate'])->name('admin.laporan.generate');
});

Route::middleware('auth')->group(function() {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});
Route::put('/admin/pengguna/{id}', [PenggunaController::class, 'update']);
