@extends('layouts.admin')

@section('title', 'Dashboard')

@php
    $header = 'Dashboard Admin';
    $description = 'Selamat datang di sistem inventaris barang sekolah SMAN 1 Cikalongwetan';
    $breadcrumb = [
        ['title' => 'Dashboard', 'url' => route('dashboard')]
    ];
@endphp

@push('styles')
<style>
    /* Custom badge styles */
    .badge-dipinjam {
        @apply bg-indigo-50 text-indigo-800 px-2.5 py-0.5 rounded-full text-xs font-medium;
    }
    .badge-dikembalikan {
        @apply bg-emerald-50 text-emerald-800 px-2.5 py-0.5 rounded-full text-xs font-medium;
    }
    .badge-terlambat {
        @apply bg-amber-50 text-amber-800 px-2.5 py-0.5 rounded-full text-xs font-medium;
    }
    .badge-diperbaiki {
        @apply bg-purple-50 text-purple-800 px-2.5 py-0.5 rounded-full text-xs font-medium;
    }
    .badge-warning {
        @apply bg-rose-50 text-rose-800 px-2.5 py-0.5 rounded-full text-xs font-medium;
    }
</style>
@endpush

@section('page-content')
<!-- Quick Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Barang -->
    <div class="glass rounded-2xl p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Barang</p>
                <p class="text-3xl font-bold text-gray-900">245</p>
                <p class="text-sm text-gray-500 mt-1">
                    <span class="text-green-600">198</span> tersedia
                </p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                <i class="fas fa-box text-blue-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full" style="width: 80%"></div>
        </div>
    </div>
    
    <!-- Peminjaman Aktif -->
    <div class="glass rounded-2xl p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Peminjaman Aktif</p>
                <p class="text-3xl font-bold text-gray-900">32</p>
                <p class="text-sm text-gray-500 mt-1">
                    <span class="text-red-600">3</span> terlambat
                </p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-xl flex items-center justify-center">
                <i class="fas fa-hand-holding text-indigo-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-2 rounded-full" style="width: 65%"></div>
        </div>
    </div>
    
    <!-- Total Pengguna -->
    <div class="glass rounded-2xl p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Pengguna</p>
                <p class="text-3xl font-bold text-gray-900">127</p>
                <p class="text-sm text-gray-500 mt-1">
                    <span class="text-green-600">95</span> aktif
                </p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-green-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
            <div class="bg-gradient-to-r from-green-500 to-green-600 h-2 rounded-full" style="width: 75%"></div>
        </div>
    </div>
    
    <!-- Transaksi Bulan Ini -->
    <div class="glass rounded-2xl p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Transaksi Bulan Ini</p>
                <p class="text-3xl font-bold text-gray-900">89</p>
                <p class="text-sm text-gray-500 mt-1">
                    <span class="text-blue-600">+12%</span> dari bulan lalu
                </p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center">
                <i class="fas fa-chart-line text-purple-600 text-xl"></i>
            </div>
        </div>
        <div class="mt-4 w-full bg-gray-200 rounded-full h-2">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2 rounded-full" style="width: 89%"></div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Recent Transactions -->
    <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Transaksi Terbaru</h3>
            <a href="{{ route('admin.transaksi.index') }}" 
               class="text-primary hover:text-primary/80 text-sm font-medium">
                Lihat Semua
            </a>
        </div>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-gray-50/50 rounded-lg hover:bg-gray-100/50 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary/20 to-secondary/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-book text-primary"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Buku Matematika</p>
                        <p class="text-sm text-gray-500">Ahmad Rizki</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="badge-dipinjam">Dipinjam</span>
                    <p class="text-xs text-gray-500 mt-1">2 jam lalu</p>
                </div>
            </div>
            
            <div class="flex items-center justify-between p-4 bg-gray-50/50 rounded-lg hover:bg-gray-100/50 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary/20 to-secondary/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-calculator text-primary"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Kalkulator Casio</p>
                        <p class="text-sm text-gray-500">Siti Aminah</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="badge-dikembalikan">Dikembalikan</span>
                    <p class="text-xs text-gray-500 mt-1">5 jam lalu</p>
                </div>
            </div>
            
            <div class="flex items-center justify-between p-4 bg-gray-50/50 rounded-lg hover:bg-gray-100/50 transition-colors">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary/20 to-secondary/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-microscope text-primary"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Mikroskop</p>
                        <p class="text-sm text-gray-500">Budi Santoso</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="badge-terlambat">Terlambat</span>
                    <p class="text-xs text-gray-500 mt-1">1 hari lalu</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="glass rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Aksi Cepat</h3>
        
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('admin.barang.create') }}" 
               class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg hover:from-blue-100 hover:to-blue-200 transition-all duration-200 group">
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-plus text-white text-xl"></i>
                    </div>
                    <p class="font-medium text-gray-900">Tambah Barang</p>
                </div>
            </a>
            
            <a href="{{ route('admin.transaksi.create') }}" 
               class="p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-lg hover:from-green-100 hover:to-green-200 transition-all duration-200 group">
                <div class="text-center">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-handshake text-white text-xl"></i>
                    </div>
                    <p class="font-medium text-gray-900">Buat Transaksi</p>
                </div>
            </a>
            
            <a href="{{ route('admin.pengguna.create') }}" 
               class="p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg hover:from-purple-100 hover:to-purple-200 transition-all duration-200 group">
                <div class="text-center">
                    <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-plus text-white text-xl"></i>
                    </div>
                    <p class="font-medium text-gray-900">Tambah User</p>
                </div>
            </a>
            
            <a href="{{ route('admin.laporan.index') }}" 
               class="p-4 bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg hover:from-amber-100 hover:to-amber-200 transition-all duration-200 group">
                <div class="text-center">
                    <div class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-bar text-white text-xl"></i>
                    </div>
                    <p class="font-medium text-gray-900">Lihat Laporan</p>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="glass rounded-2xl p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Statistik Peminjaman</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Status Distribution -->
        <div>
            <h4 class="text-sm font-medium text-gray-600 mb-4">Distribusi Status Peminjaman</h4>
            <div class="space-y-3">
                @php
                    $statusStats = $stats['statusDistribution'] ?? [
                        'dipinjam' => 25,
                        'dikembalikan' => 45,
                        'terlambat' => 8,
                        'rusak' => 2
                    ];
                    $total = array_sum($statusStats);
                @endphp
                
                @foreach($statusStats as $status => $count)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 rounded-full bg-{{ $status === 'dipinjam' ? 'blue' : ($status === 'dikembalikan' ? 'green' : ($status === 'terlambat' ? 'amber' : 'red')) }}-500"></div>
                            <span class="text-sm text-gray-600">{{ ucfirst($status) }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-gray-900">{{ $count }}</span>
                            <div class="w-20 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-{{ $status === 'dipinjam' ? 'blue' : ($status === 'dikembalikan' ? 'green' : ($status === 'terlambat' ? 'amber' : 'red')) }}-500 rounded-full" 
                                     style="width: {{ $total > 0 ? ($count / $total * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- Monthly Trend -->
        <div>
            <h4 class="text-sm font-medium text-gray-600 mb-4">Tren Bulanan</h4>
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-chart-line text-4xl mb-4"></i>
                <p>Chart akan ditampilkan di sini</p>
                <p class="text-xs">Memerlukan library charting</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Dashboard specific JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        console.log('New Admin Dashboard loaded with improved layout');
    });
</script>
@endpush 