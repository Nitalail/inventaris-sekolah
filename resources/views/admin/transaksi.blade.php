<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Transaksi - Inventaris Barang Sekolah</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.3/cdn.min.js" defer></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#6366f1',
                        secondary: '#8b5cf6',
                        accent: '#10b981',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.3s ease-out',
                        'slide-up': 'slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1)',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 6px;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 6px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        
        .gradient-text {
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            background-image: linear-gradient(135deg, #3b82f6, #8b5cf6);
        }
        
        .hover-scale {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hover-scale:hover {
            transform: scale(1.03);
        }
        
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
        .badge-rusak {
            @apply bg-rose-50 text-rose-800 px-2.5 py-0.5 rounded-full text-xs font-medium;
        }
        
        /* Glassmorphism effect */
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        
        /* Table row hover */
        .table-row-hover:hover {
            @apply bg-gray-50/50;
        }
        
        /* Smooth transitions */
        .transition-slow {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>

<body class="font-sans antialiased bg-gradient-to-br from-gray-50 via-white to-gray-100" x-data="transaksiApp()">
    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-30 w-64 glass shadow-lg border-r border-gray-200/70 transition-all duration-300 ease-in-out transform -translate-x-full lg:translate-x-0" id="sidebar">
        <div class="flex flex-col h-full">
            <div class="p-6 pb-4 flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-lg">IS</div>
                <h2 class="text-xl font-semibold text-gray-800">Inventaris Sekolah</h2>
            </div>
            
            <nav class="flex-1 px-3 overflow-y-auto">
                <ul class="space-y-1">
                    <li>
                        <a href="dashboard" class="flex items-center px-4 py-3 text-gray-600 hover:text-primary-600 hover:bg-gray-100/50 rounded-lg transition-slow">
                            <i class="fas fa-th-large w-5 mr-3 text-center"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/barang" class="flex items-center px-4 py-3 text-gray-600 hover:text-primary-600 hover:bg-gray-100/50 rounded-lg transition-slow">
                            <i class="fas fa-box w-5 mr-3 text-center"></i>
                            <span>Barang</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/transaksi" class="flex items-center px-4 py-3 text-white bg-gradient-to-r from-primary to-secondary rounded-lg shadow-sm">
                            <i class="fas fa-exchange-alt w-5 mr-3 text-center"></i>
                            <span>Transaksi</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/ruangan" class="flex items-center px-4 py-3 text-gray-600 hover:text-primary-600 hover:bg-gray-100/50 rounded-lg transition-slow">
                            <i class="fas fa-warehouse w-5 mr-3 text-center"></i>
                            <span>Ruangan</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/kategori" class="flex items-center px-4 py-3 text-gray-600 hover:text-primary-600 hover:bg-gray-100/50 rounded-lg transition-slow">
                            <i class="fas fa-clipboard-list w-5 mr-3 text-center"></i>
                            <span>Kategori</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/pengguna" class="flex items-center px-4 py-3 text-gray-600 hover:text-primary-600 hover:bg-gray-100/50 rounded-lg transition-slow">
                            <i class="fas fa-users w-5 mr-3 text-center"></i>
                            <span>Pengguna</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/laporan" class="flex items-center px-4 py-3 text-gray-600 hover:text-primary-600 hover:bg-gray-100/50 rounded-lg transition-slow">
                            <i class="fas fa-file-alt w-5 mr-3 text-center"></i>
                            <span>Laporan</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="p-4 border-t border-gray-200/70" x-data="{ open: false }">
                <div class="flex items-center space-x-3 cursor-pointer group" 
                    @click="open = !open">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center text-primary-600 font-semibold">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">{{ Auth::user()->name }}</h3>
                    </div>
                    <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200 group-hover:text-gray-600" 
                    :class="{ 'transform rotate-180': open }"></i>
                </div>

                <div x-show="open" 
                    x-transition
                    class="mt-2 space-y-1 pl-13">
                    <a href="/admin/pengaturan" 
                    class="flex items-center px-3 py-2 text-sm text-gray-600 hover:text-primary-600 hover:bg-gray-100/50 rounded-lg transition-slow">
                        <i class="fas fa-cog w-4 mr-2 text-center"></i>
                        Pengaturan
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="flex items-center w-full px-3 py-2 text-sm text-rose-500 hover:text-rose-600 hover:bg-rose-50/30 rounded-lg transition-slow">
                            <i class="fas fa-sign-out-alt w-4 mr-2 text-center text-rose-500"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>
    
    <!-- Navbar -->
    <nav class="fixed top-0 right-0 left-0 lg:left-64 h-16 glass border-b border-gray-200/70 shadow-sm z-20 px-6 flex items-center justify-between transition-all duration-300 ease-in-out">
        <button class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none transition-slow" id="sidebar-toggle">
            <i class="fas fa-bars text-xl"></i>
        </button>
        
        <div class="hidden lg:block relative w-96">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" class="w-full pl-10 pr-4 py-2 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-slow" placeholder="Cari transaksi...">
        </div>
        
        <div class="flex items-center space-x-4">
            <button class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100/50 rounded-full transition-slow">
                <i class="fas fa-bell text-xl"></i>
                <span class="absolute top-0 right-0 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
            </button>
            {{-- <button class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100/50 rounded-full transition-slow">
                <i class="fas fa-envelope text-xl"></i>
                <span class="absolute top-0 right-0 w-5 h-5 bg-blue-500 text-white text-xs rounded-full flex items-center justify-center">5</span>
            </button> --}}
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="pt-16 lg:pl-64 min-h-screen transition-all duration-300 ease-in-out">
        <div class="p-6">
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Manajemen Transaksi</h1>
                    <p class="text-gray-600">Kelola semua transaksi peminjaman dan pengembalian barang</p>
                </div>
                <div class="mt-4 md:mt-0">
                    
                </div>
            </div>

            <!-- Success Alert -->
            @if(session('success'))
            <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm flex items-start" x-data="{ show: true }" x-show="show" @click.away="show = false" style="cursor: pointer;">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <h4 class="text-sm font-medium text-green-800">Berhasil!</h4>
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
                <button class="ml-3 text-green-700 hover:text-green-900 transition-slow" @click="show = false">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif

            <!-- Error Alert -->
            @if($errors->any())
            <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm flex items-start" x-data="{ show: true }" x-show="show" @click.away="show = false" style="cursor: pointer;">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-gradient-to-r from-red-500 to-rose-500 rounded-full flex items-center justify-center text-white">
                        <i class="fas fa-exclamation"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <h4 class="text-sm font-medium text-red-800">Terjadi Kesalahan!</h4>
                    <ul class="list-disc list-inside text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button class="ml-3 text-red-700 hover:text-red-900 transition-slow" @click="show = false">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif

            <!-- Stats Cards -->
            {{-- <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white/90 glass rounded-xl shadow-sm border border-gray-200/70 p-6 hover-scale">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Transaksi</p>
                            <h3 class="text-2xl font-semibold text-gray-800 mt-1">{{ $stats['total_transactions'] ?? 0 }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                            <i class="fas fa-exchange-alt text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white/90 glass rounded-xl shadow-sm border border-gray-200/70 p-6 hover-scale">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Sedang Dipinjam</p>
                            <h3 class="text-2xl font-semibold text-gray-800 mt-1">{{ $stats['active_loans'] ?? 0 }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                            <i class="fas fa-box-open text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white/90 glass rounded-xl shadow-sm border border-gray-200/70 p-6 hover-scale">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Terlambat</p>
                            <h3 class="text-2xl font-semibold text-gray-800 mt-1">{{ $stats['overdue'] ?? 0 }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-amber-50 flex items-center justify-center text-amber-600">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white/90 glass rounded-xl shadow-sm border border-gray-200/70 p-6 hover-scale">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Transaksi Hari Ini</p>
                            <h3 class="text-2xl font-semibold text-gray-800 mt-1">{{ $stats['today_transactions'] ?? 0 }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center text-purple-600">
                            <i class="fas fa-calendar-day text-xl"></i>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Filter Card -->
            <div class="bg-white/90 glass rounded-xl shadow-sm border border-gray-200/70 mb-6">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-800">Filter Transaksi</h3>
                </div>
                
                <div class="p-6">
                    <form class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow">
                                <option value="">Semua Status</option>
                                <option value="dipinjam">Dipinjam</option>
                                <option value="dikembalikan">Dikembalikan</option>
                                <option value="terlambat">Terlambat</option>
                                <option value="diperbaiki">Diperbaiki</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                            <input type="date" id="date_from" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow">
                        </div>
                        
                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                            <input type="date" id="date_to" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow">
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-gradient-to-r from-primary to-secondary text-white px-4 py-2 rounded-lg hover:from-primary/90 hover:to-secondary/90 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow shadow-sm hover:shadow-md">
                                <i class="fas fa-filter mr-2"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Main Card -->
            <div class="bg-white/90 glass rounded-xl shadow-sm border border-gray-200/70 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Transaksi</h3>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" 
                               id="search-input"
                               class="pl-10 pr-4 py-2 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-slow" 
                               placeholder="Cari transaksi..."
                               oninput="handleSearch()">
                    </div>
                </div>
                
                <div class="p-6">
                    <!-- Table -->
                    <div class="overflow-x-auto rounded-lg border border-gray-200/70 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200/70">
                            <thead class="bg-gray-50/80">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Kode Barang</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nama Barang</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Peminjam</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tanggal Pinjam</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tenggat Kembali</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200/70" id="table-body">
                                @forelse ($transaksis as $index => $transaksi)
                                <tr class="table-row-hover transition-colors duration-150" data-id="{{ $transaksi->id }}" data-status="{{ strtolower($transaksi->status) }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaksi->kode_barang }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaksi->nama_barang }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaksi->peminjam }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaksi->tanggal_pinjam }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaksi->tanggal_kembali }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @php
                                            $badgeClass = 'badge-' . strtolower($transaksi->status);
                                        @endphp
                                        <span class="{{ $badgeClass }}">{{ $transaksi->status }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button @click="openDetailModal({{ $transaksi->id }})" class="text-indigo-600 hover:text-indigo-900 p-2 rounded-lg hover:bg-indigo-50 transition-slow">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button @click="openEditModal({{ $transaksi->id }})" class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-slow">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button @click="confirmDelete({{ $transaksi->id }})" class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-slow">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data transaksi yang ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6 flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                           Menampilkan {{ $transaksis->count() }} dari {{ $transaksis->total() }} transaksi
                        </div>
                        <div class="flex space-x-2">
                            {{ $transaksis->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Detail Transaksi -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" id="modal-detail" style="display: none;" x-show="showDetailModal" x-cloak>
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity transition-slow" @click="showDetailModal = false"></div>
            
            <div class="relative glass rounded-xl shadow-2xl border border-gray-200/70 w-full max-w-4xl transform transition-all">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Detail Transaksi</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-slow" @click="showDetailModal = false">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-md font-medium text-gray-700 mb-4 pb-2 border-b border-gray-200">Informasi Barang</h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500">Kode Barang</p>
                                    <p class="text-sm font-medium text-gray-900" x-text="currentTransaksi.kode_barang"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Nama Barang</p>
                                    <p class="text-sm font-medium text-gray-900" x-text="currentTransaksi.nama_barang"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Kategori</p>
                                    <p class="text-sm font-medium text-gray-900">Elektronik</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Kondisi</p>
                                    <p class="text-sm font-medium text-gray-900">Baik</p>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-md font-medium text-gray-700 mb-4 pb-2 border-b border-gray-200">Informasi Peminjam</h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500">Nama Peminjam</p>
                                    <p class="text-sm font-medium text-gray-900" x-text="currentTransaksi.peminjam"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Kontak</p>
                                    <p class="text-sm font-medium text-gray-900">08123456789</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Keperluan</p>
                                    <p class="text-sm font-medium text-gray-900">Pembelajaran di kelas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-4 border-t border-gray-200">
                        <h4 class="text-md font-medium text-gray-700 mb-4">Timeline Transaksi</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Tanggal Pinjam</p>
                                <p class="text-sm font-medium text-gray-900" x-text="currentTransaksi.tanggal_pinjam"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tenggat Kembali</p>
                                <p class="text-sm font-medium text-gray-900" x-text="currentTransaksi.tanggal_kembali"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Status</p>
                                <p class="text-sm font-medium">
                                    <span x-bind:class="'badge-' + currentTransaksi.status.toLowerCase()" x-text="currentTransaksi.status"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <p class="text-sm text-gray-500">Catatan</p>
                        <p class="text-sm font-medium text-gray-900 mt-1" x-text="currentTransaksi.catatan || 'Tidak ada catatan'"></p>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200/70 flex justify-end space-x-3">
                    <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow" @click="showDetailModal = false">
                        Tutup
                    </button>
                    <button type="button" class="px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-lg hover:from-primary/90 hover:to-secondary/90 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow shadow-sm hover:shadow-md" @click="showDetailModal = false; showEditModal = true;">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Edit Transaksi -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" id="modal-edit" style="display: none;" x-show="showEditModal" x-cloak>
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity transition-slow" @click="showEditModal = false"></div>
            
            <div class="relative glass rounded-xl shadow-2xl border border-gray-200/70 w-full max-w-2xl transform transition-all">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Transaksi</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-slow" @click="showEditModal = false">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="form-edit-transaksi" :action="'/admin/transaksi/' + currentTransaksi.id" method="POST" class="p-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="edit-kode-barang" class="block text-sm font-medium text-gray-700 mb-1">Kode Barang</label>
                            <input type="text" id="edit-kode-barang" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" x-model="currentTransaksi.kode_barang" readonly>
                        </div>
                        
                        <div>
                            <label for="edit-peminjam" class="block text-sm font-medium text-gray-700 mb-1">Nama Peminjam</label>
                            <input type="text" id="edit-peminjam" name="peminjam" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" x-model="currentTransaksi.peminjam">
                        </div>
                        
                        <div>
                            <label for="edit-tanggal-pinjam" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pinjam</label>
                            <input type="date" id="edit-tanggal-pinjam" name="tanggal_pinjam" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" x-model="currentTransaksi.tanggal_pinjam">
                        </div>
                        
                        <div>
                            <label for="edit-tanggal-kembali" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kembali</label>
                            <input type="date" id="edit-tanggal-kembali" name="tanggal_kembali" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" x-model="currentTransaksi.tanggal_kembali">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="edit-status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="edit-status" name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" x-model="currentTransaksi.status">
                                <option value="dipinjam">Dipinjam</option>
                                <option value="dikembalikan">Dikembalikan</option>
                                <option value="terlambat">Terlambat</option>
                                <option value="diperbaiki">Diperbaiki</option>
                                <option value="rusak">Rusak</option>
                            </select>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="edit-catatan" class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                                                        <textarea id="edit-catatan" name="catatan" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" x-model="currentTransaksi.catatan"></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow" @click="showEditModal = false">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-lg hover:from-primary/90 hover:to-secondary/90 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow shadow-sm hover:shadow-md">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Toggle sidebar for mobile
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
        });

        // Search functionality
        function handleSearch() {
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            const rows = document.querySelectorAll('#table-body tr');
            
            rows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(searchTerm) ? '' : 'none';
            });
        }

        // Filter functionality
        document.querySelector('.filter-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const status = document.getElementById('status').value.toLowerCase();
            const dateFrom = document.getElementById('date_from').value;
            const dateTo = document.getElementById('date_to').value;
            
            const rows = document.querySelectorAll('#table-body tr');
            
            rows.forEach(row => {
                const rowStatus = row.getAttribute('data-status');
                const rowDate = row.querySelector('td:nth-child(5)').textContent.trim();
                
                let show = true;
                
                // Filter by status
                if (status && rowStatus !== status) {
                    show = false;
                }
                
                // Filter by date range
                if (dateFrom || dateTo) {
                    const rowDateObj = new Date(rowDate.split('/').reverse().join('-'));
                    const startDateObj = dateFrom ? new Date(dateFrom) : null;
                    const endDateObj = dateTo ? new Date(dateTo) : null;
                    
                    if (startDateObj && rowDateObj < startDateObj) {
                        show = false;
                    }
                    if (endDateObj && rowDateObj > endDateObj) {
                        show = false;
                    }
                }
                
                row.style.display = show ? '' : 'none';
            });
        });

        // Alpine.js App
        function transaksiApp() {
            return {
                showDetailModal: false,
                showEditModal: false,
                currentTransaksi: {
                    id: null,
                    kode_barang: '',
                    nama_barang: '',
                    peminjam: '',
                    tanggal_pinjam: '',
                    tanggal_kembali: '',
                    status: '',
                    catatan: ''
                },
                
                openDetailModal(id) {
                    // In a real app, you would fetch the data from the server
                    const row = document.querySelector(`tr[data-id="${id}"]`);
                    if (row) {
                        const cells = row.querySelectorAll('td');
                        this.currentTransaksi = {
                            id: id,
                            kode_barang: cells[1].textContent.trim(),
                            nama_barang: cells[2].textContent.trim(),
                            peminjam: cells[3].textContent.trim(),
                            tanggal_pinjam: cells[4].textContent.trim(),
                            tanggal_kembali: cells[5].textContent.trim(),
                            status: cells[6].querySelector('span').textContent.trim(),
                            catatan: 'Catatan contoh untuk transaksi ini' // From server in real app
                        };
                        this.showDetailModal = true;
                    }
                },
                
                openEditModal(id) {
                    // In a real app, you would fetch the data from the server
                    const row = document.querySelector(`tr[data-id="${id}"]`);
                    if (row) {
                        const cells = row.querySelectorAll('td');
                        this.currentTransaksi = {
                            id: id,
                            kode_barang: cells[1].textContent.trim(),
                            nama_barang: cells[2].textContent.trim(),
                            peminjam: cells[3].textContent.trim(),
                            tanggal_pinjam: this.formatDateForInput(cells[4].textContent.trim()),
                            tanggal_kembali: this.formatDateForInput(cells[5].textContent.trim()),
                            status: cells[6].querySelector('span').textContent.trim(),
                            catatan: 'Catatan contoh untuk transaksi ini' // From server in real app
                        };
                        this.showEditModal = true;
                    }
                },
                
                confirmDelete(id) {
                    if (confirm('Apakah Anda yakin ingin menghapus transaksi ini?')) {
                        // In a real app, you would send a DELETE request to the server
                        fetch(`/admin/transaksi/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Remove the row from the table
                                document.querySelector(`tr[data-id="${id}"]`).remove();
                                // Show success message
                                alert('Transaksi berhasil dihapus');
                            } else {
                                alert('Gagal menghapus transaksi');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat menghapus transaksi');
                        });
                    }
                },
                
                formatDateForInput(dateString) {
                    if (!dateString) return '';
                    // Handle DD/MM/YYYY format
                    if (dateString.includes('/')) {
                        const parts = dateString.split('/');
                        if (parts.length === 3) {
                            return `${parts[2]}-${parts[1].padStart(2, '0')}-${parts[0].padStart(2, '0')}`;
                        }
                    }
                    // If already in YYYY-MM-DD format, return as is
                    return dateString;
                }
            }
        }

        // Set default dates for filter
        document.addEventListener('DOMContentLoaded', function() {
            // Set date to to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date_to').value = today;
            
            // Set date from to 30 days ago
            const thirtyDaysAgo = new Date();
            thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
            document.getElementById('date_from').value = thirtyDaysAgo.toISOString().split('T')[0];
        });
    </script>
</body>
</html>