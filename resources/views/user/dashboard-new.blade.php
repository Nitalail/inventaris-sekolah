@extends('layouts.user')

@section('title', 'Dashboard - SchoolLend')

@php
    $pageHeader = [
        'title' => 'Selamat Datang',
        'description' => 'Temukan dan pinjam barang yang Anda butuhkan untuk kegiatan belajar.'
    ];
@endphp

@section('page-content')
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 hero-stats">
        <!-- Pinjaman Aktif -->
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center text-blue-600">
                    <i class="fas fa-book text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Pinjaman Aktif</h3>
                    <p class="text-2xl font-bold text-gray-900 animate-counter">5 Item</p>
                </div>
            </div>
        </div>
        
        <!-- Total Riwayat -->
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center text-purple-600">
                    <i class="fas fa-history text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Total peminjaman</h3>
                    <p class="text-2xl font-bold text-gray-900 animate-counter">23 Transaksi</p>
                </div>
            </div>
        </div>
        
        <!-- Selesai -->
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Selesai</h3>
                    <p class="text-2xl font-bold text-gray-900 animate-counter">18 Transaksi</p>
                </div>
            </div>
        </div>
        
        <!-- Keterlambatan -->
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-red-100 to-red-200 rounded-xl flex items-center justify-center text-red-600">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Keterlambatan</h3>
                    <p class="text-2xl font-bold text-gray-900 animate-counter">0 Item</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="bg-white/70 backdrop-blur-sm rounded-3xl shadow-lg border border-gray-100 p-8 mb-8">
        <div class="flex items-center gap-3 mb-5">
            <i class="fas fa-search text-primary"></i>
            <h2 class="text-xl font-semibold text-gray-900">Cari Barang</h2>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3">
            <input type="text" 
                   class="flex-1 min-w-0 px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition" 
                   placeholder="Cari buku, alat tulis, atau peralatan lainnya...">
            <select class="px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition min-w-[150px]">
                <option value="">Semua Kategori</option>
                <option value="1">Buku Pelajaran</option>
                <option value="2">Alat Tulis</option>
                <option value="3">Peralatan Lab</option>
            </select>
            <button class="bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white px-6 py-3 rounded-xl font-semibold flex items-center justify-center gap-2 transition hover:-translate-y-0.5 hover:shadow">
                <i class="fas fa-search"></i>
                Cari
            </button>
        </div>
    </div>

    <!-- Available Items -->
    <div class="bg-white/70 backdrop-blur-sm rounded-3xl shadow-lg border border-gray-100 p-8 mb-8">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <i class="fas fa-boxes text-primary"></i>
                <h2 class="text-xl font-semibold text-gray-900">Barang Tersedia</h2>
            </div>
            <div class="text-sm text-gray-500">
                Menampilkan 3 dari 48 barang
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Sample Item 1 -->
            <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                            <i class="fas fa-book text-xl"></i>
                        </div>
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-1 rounded-full">Tersedia</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Buku Matematika</h3>
                    <p class="text-sm text-gray-600 mb-4">Buku pelajaran matematika kelas XII</p>
                    <div class="flex items-center justify-between text-sm mb-4">
                        <span class="text-gray-500">Stok: <span class="font-medium text-gray-900">15 item</span></span>
                        <span class="text-gray-500">Kondisi: <span class="font-medium text-green-600">Baik</span></span>
                    </div>
                    <button class="w-full bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white py-2.5 rounded-xl font-medium transition hover:-translate-y-0.5 hover:shadow">
                        <i class="fas fa-hand-holding mr-2"></i>
                        Pinjam Sekarang
                    </button>
                </div>
            </div>

            <!-- Sample Item 2 -->
            <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center text-green-600 group-hover:scale-110 transition-transform">
                            <i class="fas fa-calculator text-xl"></i>
                        </div>
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-1 rounded-full">Tersedia</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Kalkulator Scientific</h3>
                    <p class="text-sm text-gray-600 mb-4">Kalkulator ilmiah untuk perhitungan</p>
                    <div class="flex items-center justify-between text-sm mb-4">
                        <span class="text-gray-500">Stok: <span class="font-medium text-gray-900">8 item</span></span>
                        <span class="text-gray-500">Kondisi: <span class="font-medium text-green-600">Baik</span></span>
                    </div>
                    <button class="w-full bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white py-2.5 rounded-xl font-medium transition hover:-translate-y-0.5 hover:shadow">
                        <i class="fas fa-hand-holding mr-2"></i>
                        Pinjam Sekarang
                    </button>
                </div>
            </div>

            <!-- Sample Item 3 -->
            <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform">
                            <i class="fas fa-microscope text-xl"></i>
                        </div>
                        <span class="bg-amber-100 text-amber-800 text-xs font-medium px-2.5 py-1 rounded-full">Terbatas</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Mikroskop Digital</h3>
                    <p class="text-sm text-gray-600 mb-4">Mikroskop untuk praktikum biologi</p>
                    <div class="flex items-center justify-between text-sm mb-4">
                        <span class="text-gray-500">Stok: <span class="font-medium text-gray-900">3 item</span></span>
                        <span class="text-gray-500">Kondisi: <span class="font-medium text-green-600">Baik</span></span>
                    </div>
                    <button class="w-full bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white py-2.5 rounded-xl font-medium transition hover:-translate-y-0.5 hover:shadow">
                        <i class="fas fa-hand-holding mr-2"></i>
                        Pinjam Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('New User Dashboard loaded');
    });
</script>
@endpush 