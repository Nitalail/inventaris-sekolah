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
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            },
                        },
                        slideUp: {
                            '0%': {
                                transform: 'translateY(10px)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }

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

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
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

        /* Notification highlight styles */
        .notification-highlight {
            position: relative;
            animation: highlight-glow 2s ease-in-out;
        }

        .notification-highlight::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #3b82f6, #8b5cf6, #10b981, #f59e0b);
            border-radius: 8px;
            z-index: -1;
            animation: highlight-border 2s ease-in-out;
        }

        @keyframes highlight-glow {
            0%, 100% { box-shadow: 0 0 0 rgba(59, 130, 246, 0); }
            50% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.5); }
        }

        @keyframes highlight-border {
            0%, 100% { opacity: 0; }
            50% { opacity: 1; }
        }

        /* Enhanced pulse animation for highlighted rows */
        .pulse-highlight {
            animation: pulse-highlight 1.5s ease-in-out 3;
        }

        @keyframes pulse-highlight {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        /* Context notification styles */
        .context-notification {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-left: 4px solid #3b82f6;
        }

        /* Smart navigation indicator */
        .smart-nav-indicator {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: rgba(59, 130, 246, 0.9);
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            animation: slide-in-right 0.3s ease-out;
        }

        @keyframes slide-in-right {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* Returned transaction styles */
        .transaction-final {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-left: 4px solid #22c55e;
        }

        .transaction-readonly {
            opacity: 0.9;
        }

        /* Disabled button styles */
        .btn-disabled {
            background-color: #f9fafb !important;
            color: #d1d5db !important;
            cursor: not-allowed !important;
            border-color: #e5e7eb !important;
        }

        .btn-disabled:hover {
            background-color: #f9fafb !important;
            color: #d1d5db !important;
            transform: none !important;
        }

        /* Final badge animation */
        .badge-final {
            animation: final-pulse 2s ease-in-out infinite;
        }

        @keyframes final-pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
            50% { box-shadow: 0 0 0 4px rgba(34, 197, 94, 0); }
        }
    </style>
</head>

<body class="font-sans antialiased bg-gradient-to-br from-gray-50 via-white to-gray-100" x-data="transaksiApp()">
    @include('partials.notification-system', [
        'clickUrl' => '/admin/laporan',
        'actionText' => 'lihat laporan'
    ])
    <!-- Sidebar -->
    <aside
        class="fixed inset-y-0 left-0 z-30 w-64 glass shadow-lg border-r border-gray-200/70 transition-all duration-300 ease-in-out transform -translate-x-full lg:translate-x-0"
        id="sidebar">
        <div class="flex flex-col h-full">
            <div class="p-6 pb-4 flex items-center space-x-3">
                <div
                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-lg">
                    IS</div>
                <h2 class="text-xl font-semibold text-gray-800">Inventaris Sekolah</h2>
            </div>

            <nav class="flex-1 px-3 overflow-y-auto">
                <ul class="space-y-1">
                    <li>
                        <a href="dashboard"
                            class="flex items-center px-4 py-3 text-gray-600 hover:text-primary-600 hover:bg-gray-100/50 rounded-lg transition-slow">
                            <i class="fas fa-th-large w-5 mr-3 text-center"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/barang"
                            class="flex items-center px-4 py-3 text-gray-600 hover:text-primary-600 hover:bg-gray-100/50 rounded-lg transition-slow">
                            <i class="fas fa-box w-5 mr-3 text-center"></i>
                            <span>Barang</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/transaksi"
                            class="flex items-center px-4 py-3 text-white bg-gradient-to-r from-primary to-secondary rounded-lg shadow-sm">
                            <i class="fas fa-exchange-alt w-5 mr-3 text-center"></i>
                            <span>Transaksi</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/ruangan"
                            class="flex items-center px-4 py-3 text-gray-600 hover:text-primary-600 hover:bg-gray-100/50 rounded-lg transition-slow">
                            <i class="fas fa-warehouse w-5 mr-3 text-center"></i>
                            <span>Ruangan</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/kategori"
                            class="flex items-center px-4 py-3 text-gray-600 hover:text-primary-600 hover:bg-gray-100/50 rounded-lg transition-slow">
                            <i class="fas fa-clipboard-list w-5 mr-3 text-center"></i>
                            <span>Kategori</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/pengguna"
                            class="flex items-center px-4 py-3 text-gray-600 hover:text-primary-600 hover:bg-gray-100/50 rounded-lg transition-slow">
                            <i class="fas fa-users w-5 mr-3 text-center"></i>
                            <span>Pengguna</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/laporan"
                            class="flex items-center px-4 py-3 text-gray-600 hover:text-primary-600 hover:bg-gray-100/50 rounded-lg transition-slow">
                            <i class="fas fa-file-alt w-5 mr-3 text-center"></i>
                            <span>Laporan</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="p-4 border-t border-gray-200/70" x-data="{ open: false }">
                <div class="flex items-center space-x-3 cursor-pointer group" @click="open = !open">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center text-primary-600 font-semibold">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">{{ Auth::user()->name }}</h3>
                    </div>
                    <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200 group-hover:text-gray-600"
                        :class="{ 'transform rotate-180': open }"></i>
                </div>

                <div x-show="open" x-transition class="mt-2 space-y-1 pl-13">
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
    <nav
        class="fixed top-0 right-0 left-0 lg:left-64 h-16 glass border-b border-gray-200/70 shadow-sm z-20 px-6 flex items-center justify-between transition-all duration-300 ease-in-out">
        <button class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none transition-slow"
            id="sidebar-toggle">
            <i class="fas fa-bars text-xl"></i>
        </button>

        <div class="hidden lg:block relative w-96">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text"
                class="w-full pl-10 pr-4 py-2 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-slow"
                placeholder="Cari transaksi...">
        </div>

        <div class="flex items-center space-x-4">
            @include('partials.admin-notifications')
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
            @if (session('success'))
                <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm flex items-start"
                    x-data="{ show: true }" x-show="show" @click.away="show = false" style="cursor: pointer;">
                    <div class="flex-shrink-0">
                        <div
                            class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white">
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
            @if ($errors->any())
                <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm flex items-start"
                    x-data="{ show: true }" x-show="show" @click.away="show = false" style="cursor: pointer;">
                    <div class="flex-shrink-0">
                        <div
                            class="w-8 h-8 bg-gradient-to-r from-red-500 to-rose-500 rounded-full flex items-center justify-center text-white">
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
                            <select id="status"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow">
                                <option value="">Semua Status</option>
                                <option value="dipinjam">Dipinjam</option>
                                <option value="dikembalikan">Dikembalikan</option>
                                <option value="terlambat">Terlambat</option>
                                <option value="diperbaiki">Diperbaiki</option>
                            </select>
                        </div>

                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Dari
                                Tanggal</label>
                            <input type="date" id="date_from"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow">
                        </div>

                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Sampai
                                Tanggal</label>
                            <input type="date" id="date_to"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow">
                        </div>

                        <div class="flex items-end">
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-primary to-secondary text-white px-4 py-2 rounded-lg hover:from-primary/90 hover:to-secondary/90 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow shadow-sm hover:shadow-md">
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
                        <input type="text" id="search-input"
                            class="pl-10 pr-4 py-2 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-slow"
                            placeholder="Cari transaksi..." oninput="handleSearch()">
                    </div>
                </div>

                <div class="p-6">
                    <!-- Table -->
                    <div class="overflow-x-auto rounded-lg border border-gray-200/70 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200/70">
                            <thead class="bg-gray-50/80">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        ID</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Kode Barang</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Nama Barang</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Peminjam</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Tanggal Pinjam</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Tenggat Kembali</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Status</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200/70" id="table-body">
                                @forelse ($transaksis as $index => $transaksi)
                                    @php
                                        $isHighlighted = false;
                                        $highlightClass = '';
                                        $isReturned = $transaksi->status === 'dikembalikan';
                                        
                                        // Set final status styling for returned transactions
                                        if ($isReturned) {
                                            $highlightClass = 'transaction-final transaction-readonly';
                                        }
                                        
                                        // Override with highlight if specified
                                        if (isset($highlightId)) {
                                            // Check if this row should be highlighted
                                            if ($highlightId === 'transaction_' . $transaksi->id) {
                                                $isHighlighted = true;
                                                $highlightClass = $isReturned ? 'transaction-final bg-yellow-200 border-l-4 border-yellow-600 shadow-lg' : 'bg-yellow-100 border-l-4 border-yellow-500 shadow-lg';
                                            } elseif ($highlightId === 'report_' . $transaksi->id) {
                                                $isHighlighted = true;
                                                $highlightClass = $isReturned ? 'transaction-final bg-blue-200 border-l-4 border-blue-600 shadow-lg' : 'bg-blue-100 border-l-4 border-blue-500 shadow-lg';
                                            } elseif (str_contains($highlightId, 'item_') && $transaksi->sub_barang_ids) {
                                                // Check if any sub barang in this transaction matches
                                                $subBarangIds = json_decode($transaksi->sub_barang_ids, true) ?? [];
                                                $highlightItemId = str_replace('item_', '', $highlightId);
                                                if (in_array($highlightItemId, $subBarangIds)) {
                                                    $isHighlighted = true;
                                                    $highlightClass = $isReturned ? 'transaction-final bg-red-200 border-l-4 border-red-600 shadow-lg' : 'bg-red-100 border-l-4 border-red-500 shadow-lg';
                                                }
                                            } elseif (str_contains($highlightId, 'barang_')) {
                                                // Check if barang matches
                                                $highlightBarangId = str_replace('barang_', '', $highlightId);
                                                if ($transaksi->barang_id == $highlightBarangId) {
                                                    $isHighlighted = true;
                                                    $highlightClass = $isReturned ? 'transaction-final bg-green-200 border-l-4 border-green-600 shadow-lg' : 'bg-green-100 border-l-4 border-green-500 shadow-lg';
                                                }
                                            }
                                        }
                                    @endphp
                                    <tr class="table-row-hover transition-colors duration-150 {{ $highlightClass }}"
                                        data-id="{{ $transaksi->id }}"
                                        data-status="{{ strtolower($transaksi->status) }}"
                                        data-highlighted="{{ $isHighlighted ? 'true' : 'false' }}"
                                        data-returned="{{ $isReturned ? 'true' : 'false' }}"
                                        id="transaction-row-{{ $transaksi->id }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $transaksi->kode_barang }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $transaksi->nama_barang }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $transaksi->peminjam }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $transaksi->tanggal_pinjam }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $transaksi->tanggal_kembali }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @php
                                                $badgeClass = 'badge-' . strtolower($transaksi->status);
                                                $isReturned = $transaksi->status === 'dikembalikan';
                                            @endphp
                                            <div class="flex items-center space-x-2">
                                                <span class="{{ $badgeClass }}">{{ $transaksi->status }}</span>
                                                @if($isReturned)
                                                    {{-- <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200 badge-final">
                                                        <i class="fas fa-lock mr-1"></i>
                                                        Final
                                                    </span> --}}
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <!-- View Detail Button - Always Available -->
                                                <button @click="openDetailModal({{ $transaksi->id }})"
                                                    class="text-indigo-600 hover:text-indigo-900 p-2 rounded-lg hover:bg-indigo-50 transition-slow"
                                                    title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                @if($isReturned)
                                                    <!-- Returned Transaction - Read Only -->
                                                    <button disabled
                                                        class="text-gray-400 p-2 rounded-lg cursor-not-allowed opacity-50"
                                                        title="Transaksi sudah dikembalikan dan tidak dapat diubah">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button disabled
                                                        class="text-gray-400 p-2 rounded-lg cursor-not-allowed opacity-50"
                                                        title="Transaksi sudah dikembalikan dan tidak dapat dihapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @else
                                                    <!-- Active Transaction - Can Edit/Delete -->
                                                    <button @click="openEditModal({{ $transaksi->id }})"
                                                        class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-slow"
                                                        title="Edit Transaksi">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button @click="confirmDelete({{ $transaksi->id }})"
                                                        class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-slow"
                                                        title="Hapus Transaksi">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">Tidak
                                            ada data transaksi yang ditemukan.</td>
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
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" id="modal-detail" style="display: none;"
            x-show="showDetailModal" x-cloak>
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity transition-slow"
                @click="showDetailModal = false"></div>

            <div
                class="relative glass rounded-xl shadow-2xl border border-gray-200/70 w-full max-w-4xl transform transition-all">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Detail Transaksi</h3>
                    <button type="button"
                        class="text-gray-400 hover:text-gray-500 focus:outline-none transition-slow"
                        @click="showDetailModal = false">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-md font-medium text-gray-700 mb-4 pb-2 border-b border-gray-200">Informasi
                                Barang</h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500">Kode Barang</p>
                                    <p class="text-sm font-medium text-gray-900"
                                        x-text="currentTransaksi.kode_barang"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Nama Barang</p>
                                    <p class="text-sm font-medium text-gray-900"
                                        x-text="currentTransaksi.nama_barang"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Kategori</p>
                                    <p class="text-sm font-medium text-gray-900">Elektronik</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm text-gray-500">Kode Sub Barang</p>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        <template
                                            x-if="currentTransaksi.sub_barang_codes && currentTransaksi.sub_barang_codes.length > 0">
                                            <template x-for="code in currentTransaksi.sub_barang_codes"
                                                :key="code">
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                                    <i class="fas fa-tag mr-1"></i>
                                                    <span x-text="code"></span>
                                                </span>
                                            </template>
                                        </template>
                                        <template
                                            x-if="!currentTransaksi.sub_barang_codes || currentTransaksi.sub_barang_codes.length === 0">
                                            <span class="text-xs text-gray-400 italic">Tidak ada kode sub barang</span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-md font-medium text-gray-700 mb-4 pb-2 border-b border-gray-200">Informasi
                                Peminjam</h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500">Nama Peminjam</p>
                                    <p class="text-sm font-medium text-gray-900" x-text="currentTransaksi.peminjam">
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Kontak</p>
                                    <p class="text-sm font-medium text-gray-900">08123456789</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Keperluan</p>
                                    <p class="text-sm font-medium text-gray-900"
                                        x-text="currentTransaksi.keperluan || 'Tidak ada keperluan'"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-4 border-t border-gray-200">
                        <h4 class="text-md font-medium text-gray-700 mb-4">Timeline Transaksi</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Tanggal Pinjam</p>
                                <p class="text-sm font-medium text-gray-900" x-text="currentTransaksi.tanggal_pinjam">
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tenggat Kembali</p>
                                <p class="text-sm font-medium text-gray-900"
                                    x-text="currentTransaksi.tanggal_kembali"></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Status</p>
                                <p class="text-sm font-medium">
                                    <span x-bind:class="'badge-' + currentTransaksi.status.toLowerCase()"
                                        x-text="currentTransaksi.status"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <p class="text-sm text-gray-500">Catatan</p>
                        <p class="text-sm font-medium text-gray-900 mt-1"
                            x-text="currentTransaksi.catatan || 'Tidak ada catatan'"></p>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200/70 flex justify-end space-x-3">
                    <button type="button"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow"
                        @click="showDetailModal = false">
                        Tutup
                    </button>
                    <button type="button"
                        class="px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-lg hover:from-primary/90 hover:to-secondary/90 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow shadow-sm hover:shadow-md"
                        @click="showDetailModal = false; showEditModal = true;">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Edit Transaksi -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" id="modal-edit" style="display: none;"
            x-show="showEditModal" x-cloak>
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity transition-slow"
                @click="showEditModal = false"></div>

            <div
                class="relative glass rounded-xl shadow-2xl border border-gray-200/70 w-full max-w-2xl transform transition-all">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Transaksi</h3>
                    <button type="button"
                        class="text-gray-400 hover:text-gray-500 focus:outline-none transition-slow"
                        @click="showEditModal = false">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="form-edit-transaksi" :action="'/admin/transaksi/' + currentTransaksi.id" method="POST"
                    class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="edit-kode-barang" class="block text-sm font-medium text-gray-700 mb-1">Kode
                                Barang</label>
                            <input type="text" id="edit-kode-barang"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                x-model="currentTransaksi.kode_barang" readonly>
                        </div>

                        <div>
                            <label for="edit-peminjam" class="block text-sm font-medium text-gray-700 mb-1">Nama
                                Peminjam</label>
                            <input type="text" id="edit-peminjam" name="peminjam"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                x-model="currentTransaksi.peminjam">
                        </div>

                        <div>
                            <label for="edit-tanggal-pinjam"
                                class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pinjam</label>
                            <input type="date" id="edit-tanggal-pinjam" name="tanggal_pinjam"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                x-model="currentTransaksi.tanggal_pinjam">
                        </div>

                        <div>
                            <label for="edit-tanggal-kembali"
                                class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kembali</label>
                            <input type="date" id="edit-tanggal-kembali" name="tanggal_kembali"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                x-model="currentTransaksi.tanggal_kembali">
                        </div>

                        <div class="md:col-span-2">
                            <label for="edit-status"
                                class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="edit-status" name="status"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                x-model="currentTransaksi.status">
                                <option value="dipinjam">Dipinjam</option>
                                <option value="dikembalikan">Dikembalikan</option>
                                <option value="terlambat">Terlambat</option>
                                <option value="diperbaiki">Diperbaiki</option>
                                <option value="rusak">Rusak</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label for="edit-catatan"
                                class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <textarea id="edit-catatan" name="catatan" rows="3"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                x-model="currentTransaksi.catatan"></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow"
                            @click="showEditModal = false">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-lg hover:from-primary/90 hover:to-secondary/90 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow shadow-sm hover:shadow-md">
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
        const filterForm = document.querySelector('.filter-form');
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const statusInput = document.getElementById('status');
                const dateFromInput = document.getElementById('date_from');
                const dateToInput = document.getElementById('date_to');
                
                const status = statusInput ? statusInput.value.toLowerCase() : '';
                const dateFrom = dateFromInput ? dateFromInput.value : '';
                const dateTo = dateToInput ? dateToInput.value : '';

                const rows = document.querySelectorAll('#table-body tr');

                rows.forEach(row => {
                    const rowStatus = row.getAttribute('data-status');
                    const dateCell = row.querySelector('td:nth-child(5)');
                    const rowDate = dateCell ? dateCell.textContent.trim() : '';

                    let show = true;

                    // Filter by status
                    if (status && rowStatus !== status) {
                        show = false;
                    }

                    // Filter by date range
                    if ((dateFrom || dateTo) && rowDate) {
                        try {
                            const rowDateObj = new Date(rowDate.split('/').reverse().join('-'));
                            const startDateObj = dateFrom ? new Date(dateFrom) : null;
                            const endDateObj = dateTo ? new Date(dateTo) : null;

                            if (startDateObj && rowDateObj < startDateObj) {
                                show = false;
                            }
                            if (endDateObj && rowDateObj > endDateObj) {
                                show = false;
                            }
                        } catch (error) {
                            console.error('Error parsing date:', error);
                        }
                    }

                    row.style.display = show ? '' : 'none';
                });
            });
        }

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
                    // Fetch transaction data with sub barang codes from server
                    fetch(`/admin/transaksi/${id}/detail`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.currentTransaksi = {
                                    id: data.transaksi.id,
                                    kode_barang: data.transaksi.kode_barang,
                                    nama_barang: data.transaksi.nama_barang,
                                    peminjam: data.transaksi.peminjam,
                                    tanggal_pinjam: data.transaksi.tanggal_pinjam,
                                    tanggal_kembali: data.transaksi.tanggal_kembali,
                                    status: data.transaksi.status,
                                    catatan: data.transaksi.catatan || 'Tidak ada catatan',
                                    keperluan: data.transaksi.keperluan || 'Tidak ada keperluan',
                                    sub_barang_codes: data.transaksi.sub_barang_codes || []
                                };
                                this.showDetailModal = true;
                            } else {
                                // Fallback to table data if API fails
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
                                        catatan: 'Tidak ada catatan',
                                        keperluan: 'Tidak ada keperluan',
                                        sub_barang_codes: []
                                    };
                                    this.showDetailModal = true;
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching transaction details:', error);
                            // Fallback to table data
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
                                    catatan: 'Tidak ada catatan',
                                    keperluan: 'Tidak ada keperluan',
                                    sub_barang_codes: []
                                };
                                this.showDetailModal = true;
                            }
                        });
                },

                openEditModal(id) {
                    // Check if transaction is returned (read-only)
                    const row = document.querySelector(`tr[data-id="${id}"]`);
                    if (row) {
                        const cells = row.querySelectorAll('td');
                        const status = cells[6].querySelector('span').textContent.trim();
                        
                        // 🔒 PROTEKSI: Cek jika transaksi sudah dikembalikan
                        if (status === 'dikembalikan') {
                            if (window.showNotification) {
                                window.showNotification('warning', 'Transaksi yang sudah dikembalikan tidak dapat diubah. Status sudah final.', false);
                            } else {
                                alert('Transaksi yang sudah dikembalikan tidak dapat diubah. Status sudah final.');
                            }
                            return;
                        }

                        // Proceed with normal edit for non-returned transactions
                        this.currentTransaksi = {
                            id: id,
                            kode_barang: cells[1].textContent.trim(),
                            nama_barang: cells[2].textContent.trim(),
                            peminjam: cells[3].textContent.trim(),
                            tanggal_pinjam: this.formatDateForInput(cells[4].textContent.trim()),
                            tanggal_kembali: this.formatDateForInput(cells[5].textContent.trim()),
                            status: status,
                            catatan: 'Catatan contoh untuk transaksi ini' // From server in real app
                        };
                        this.showEditModal = true;
                    }
                },

                confirmDelete(id) {
                    // Check if transaction is returned (read-only)
                    const row = document.querySelector(`tr[data-id="${id}"]`);
                    if (row) {
                        const cells = row.querySelectorAll('td');
                        const status = cells[6].querySelector('span').textContent.trim();
                        
                        // 🔒 PROTEKSI: Cek jika transaksi sudah dikembalikan
                        if (status === 'dikembalikan') {
                            if (window.showNotification) {
                                window.showNotification('warning', 'Transaksi yang sudah dikembalikan tidak dapat dihapus. Data sudah final untuk keperluan audit.', false);
                            } else {
                                alert('Transaksi yang sudah dikembalikan tidak dapat dihapus. Data sudah final untuk keperluan audit.');
                            }
                            return;
                        }
                    }

                    if (confirm('Apakah Anda yakin ingin menghapus transaksi ini?')) {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]');
                        if (!csrfToken) {
                            console.error('CSRF token not found');
                            alert('Terjadi kesalahan: CSRF token tidak ditemukan');
                            return;
                        }

                        // Send DELETE request to server
                        fetch(`/admin/transaksi/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Remove the row from the table
                                    const rowToRemove = document.querySelector(`tr[data-id="${id}"]`);
                                    if (rowToRemove) {
                                        rowToRemove.remove();
                                    }
                                    // Show success message
                                    if (window.showNotification) {
                                        window.showNotification('success', data.message, false);
                                    } else {
                                        alert(data.message);
                                    }
                                } else {
                                    if (window.showNotification) {
                                        window.showNotification('error', data.message || 'Gagal menghapus transaksi', false);
                                    } else {
                                        alert(data.message || 'Gagal menghapus transaksi');
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                if (window.showNotification) {
                                    window.showNotification('error', 'Terjadi kesalahan saat menghapus transaksi', false);
                                } else {
                                    alert('Terjadi kesalahan saat menghapus transaksi');
                                }
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
            // Set default dates with null checks
            const dateToInput = document.getElementById('date_to');
            const dateFromInput = document.getElementById('date_from');
            
            if (dateToInput) {
                const today = new Date().toISOString().split('T')[0];
                dateToInput.value = today;
            }

            if (dateFromInput) {
                const thirtyDaysAgo = new Date();
                thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
                dateFromInput.value = thirtyDaysAgo.toISOString().split('T')[0];
            }

            // Smart Navigation: Handle highlighting and auto-scroll
            const urlParams = new URLSearchParams(window.location.search);
            const highlightParam = urlParams.get('highlight');
            const filterParam = urlParams.get('filter');
            const statusParam = urlParams.get('status');
            const conditionParam = urlParams.get('condition');
            const stockParam = urlParams.get('stock');

            // Show filter context if navigated from notification
            if (filterParam || statusParam || conditionParam || stockParam) {
                showFilterContext(filterParam, statusParam, conditionParam, stockParam);
            }

            // Auto-scroll to highlighted row
            if (highlightParam) {
                setTimeout(() => {
                    const highlightedRow = document.querySelector('[data-highlighted="true"]');
                    if (highlightedRow) {
                        highlightedRow.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'center' 
                        });
                        
                        // Add enhanced pulsing animation
                        highlightedRow.classList.add('pulse-highlight');
                        setTimeout(() => {
                            highlightedRow.classList.remove('pulse-highlight');
                        }, 4500);

                        console.log(`🎯 Highlighted transaction: ${highlightParam}`);
                        
                        // Show context notification
                        const contextMessage = getHighlightContextMessage(highlightParam);
                        if (contextMessage && window.showNotification) {
                            window.showNotification('info', contextMessage, false);
                        }
                    }
                }, 500);
            }

            // Enhanced notification click handler for this page
            setupNotificationClickHandler();
        });

        // Show filter context notification
        function showFilterContext(filter, status, condition, stock) {
            let contextMessage = '🔍 Filter diterapkan: ';
            const contexts = [];

            if (filter) {
                switch(filter) {
                    case 'inventory':
                        contexts.push('Transaksi terkait inventori');
                        break;
                    case 'transaction':
                        contexts.push('Semua transaksi');
                        break;
                    case 'room':
                        contexts.push('Transaksi berdasarkan ruangan');
                        break;
                }
            }

            if (status) {
                switch(status) {
                    case 'terlambat':
                        contexts.push('Peminjaman terlambat');
                        break;
                    default:
                        contexts.push(`Status: ${status}`);
                }
            }

            if (condition) {
                switch(condition) {
                    case 'rusak_berat':
                        contexts.push('Barang kondisi kritis');
                        break;
                    default:
                        contexts.push(`Kondisi: ${condition}`);
                }
            }

            if (stock) {
                switch(stock) {
                    case 'low':
                        contexts.push('Stok menipis');
                        break;
                }
            }

            if (contexts.length > 0) {
                contextMessage += contexts.join(', ');
                if (window.showNotification) {
                    window.showNotification('info', contextMessage, false);
                }
            }
        }

        // Get context message for highlighted item
        function getHighlightContextMessage(highlightParam) {
            if (highlightParam.startsWith('transaction_')) {
                return '📋 Menampilkan transaksi dari notifikasi peminjaman terlambat';
            } else if (highlightParam.startsWith('report_')) {
                return '📊 Menampilkan transaksi terkait laporan yang baru dibuat';
            } else if (highlightParam.startsWith('item_')) {
                return '⚠️ Menampilkan transaksi dengan barang kondisi kritis';
            } else if (highlightParam.startsWith('barang_')) {
                return '📦 Menampilkan transaksi dengan barang stok menipis';
            }
            return null;
        }



        // Setup enhanced notification click handler
        function setupNotificationClickHandler() {
            // Wait for showNotification to be available
            if (typeof window.showNotification === 'undefined') {
                setTimeout(setupNotificationClickHandler, 100);
                return;
            }

            // Override the notification click handler to include highlight scroll
            const originalShowNotification = window.showNotification;
            window.showNotification = function(type, message, clickable = false, url = null, actionText = '') {
                originalShowNotification(type, message, clickable, url, actionText);
                
                // If this is a transaction-related notification and we're on transaction page
                if (url && url.includes('/admin/transaksi') && url.includes('highlight=')) {
                    setTimeout(() => {
                        try {
                            const urlObj = new URL(url, window.location.origin);
                            const highlightParam = urlObj.searchParams.get('highlight');
                            if (highlightParam) {
                                const highlightedRow = document.querySelector(`[id*="${highlightParam.replace(/\w+_/, '')}"]`);
                                if (highlightedRow) {
                                    highlightedRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                }
                            }
                        } catch (error) {
                            console.error('Error in notification click handler:', error);
                        }
                    }, 1000);
                }
            };
        }
    </script>
</body>

</html>
