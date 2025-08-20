<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Inventaris Barang Sekolah</title>
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
</head>

<body class="font-sans antialiased bg-gradient-to-br from-gray-50 via-white to-gray-100" x-data="dashboardApp()">
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
                        <a href="dashboard" class="flex items-center px-4 py-3 text-white bg-gradient-to-r from-primary to-secondary rounded-lg shadow-sm">
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
                        <a href="/admin/transaksi" class="flex items-center px-4 py-3 text-gray-600 hover:text-primary-600 hover:bg-gray-100/50 rounded-lg transition-slow">
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
            <input type="text" class="w-full pl-10 pr-4 py-2 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-slow" placeholder="Cari barang, transaksi, atau kategori...">
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
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
                    <p class="text-gray-600">Selamat datang kembali, Admin Sekolah!</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                        <i class="fas fa-calendar-alt"></i>
                        <span x-text="currentDate"></span>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Sub Barang -->
                <div class="bg-white/90 glass rounded-xl shadow-sm border border-gray-200/70 p-6 hover:shadow-md transition-slow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Sub Barang</p>
                            <h3 class="text-2xl font-semibold text-gray-800 mt-1" x-text="stats.total_items">0</h3>

                        </div>
                        <div class="w-12 h-12 rounded-full bg-cyan-50 flex items-center justify-center text-cyan-600">
                            <i class="fas fa-box text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Item Dipinjam -->
                <div class="bg-white/90 glass rounded-xl shadow-sm border border-gray-200/70 p-6 hover:shadow-md transition-slow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Item Dipinjam</p>
                            <h3 class="text-2xl font-semibold text-gray-800 mt-1" x-text="stats.borrowed_items">0</h3>

                        </div>
                        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                            <i class="fas fa-hand-holding text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Kategori -->
                <div class="bg-white/90 glass rounded-xl shadow-sm border border-gray-200/70 p-6 hover:shadow-md transition-slow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Kategori</p>
                            <h3 class="text-2xl font-semibold text-gray-800 mt-1" x-text="stats.categories">0</h3>

                        </div>
                        <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center text-purple-600">
                            <i class="fas fa-tag text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Ruangan -->
                <div class="bg-white/90 glass rounded-xl shadow-sm border border-gray-200/70 p-6 hover:shadow-md transition-slow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Ruangan</p>
                            <h3 class="text-2xl font-semibold text-gray-800 mt-1" x-text="stats.rooms">0</h3>

                        </div>
                        <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                            <i class="fas fa-building text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Peminjaman Terbaru -->
                <div class="lg:col-span-2 bg-white/90 glass rounded-xl shadow-sm border border-gray-200/70 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-800">Peminjaman Terbaru</h3>
                        <a href="/admin/transaksi" class="text-sm text-primary-600 hover:text-primary-800 transition-slow">
                            Lihat Semua <i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    </div>
                    
                    <div class="p-6">
                        <div class="overflow-x-auto rounded-lg border border-gray-200/70 shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200/70">
                                <thead class="bg-gray-50/80">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nama Barang</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Peminjam</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Jumlah</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tanggal</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200/70">
                                    @forelse($recentTransactions as $transaction)
                                    <tr class="table-row-hover transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction['id'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction['item_name'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction['peminjam'] ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $transaction['quantity'] }} item</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction['date'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @php
                                                $status = strtolower(str_replace(' ', '-', $transaction['status']));
                                                $badgeClass = match($status) {
                                                    'menunggu-persetujuan' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800',
                                                    'dipinjam' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800',
                                                    'dikonfirmasi' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800',
                                                    'dikembalikan' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800',
                                                    'terlambat' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800',
                                                    'rusak' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800',
                                                    default => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800'
                                                };
                                            @endphp
                                            <span class="{{ $badgeClass }}">{{ $transaction['status'] }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada peminjaman terbaru</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Statistik Tambahan -->
                <div class="space-y-6">
                    <!-- Total Peminjaman -->
                    <div class="bg-white/90 glass rounded-xl shadow-sm border border-gray-200/70 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-800">Statistik Peminjaman</h4>
                            <i class="fas fa-chart-bar text-gray-400"></i>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Peminjaman</span>
                                <span class="text-sm font-semibold text-gray-900" x-text="stats.total_transactions">0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Aktif</span>
                                <span class="text-sm font-semibold text-blue-600" x-text="stats.active_loans">0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Pending</span>
                                <span class="text-sm font-semibold text-yellow-600" x-text="stats.pending_loans">0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Selesai</span>
                                <span class="text-sm font-semibold text-green-600" x-text="stats.completed_loans">0</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Barang yang Sering Dipinjam -->
                    <div class="bg-white/90 glass rounded-xl shadow-sm border border-gray-200/70 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-800">Barang yang Sering Dipinjam</h4>
                            <i class="fas fa-star text-gray-400"></i>
                        </div>
                        <div class="space-y-3">
                            @forelse($mostBorrowedItems ?? [] as $index => $item)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gradient-to-r from-amber-400 to-orange-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $item['item_name'] }}</p>
                                            <p class="text-xs text-gray-500">{{ $item['borrow_count'] }} kali dipinjam</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                            {{ $item['borrow_count'] }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="fas fa-chart-line text-gray-300 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-500">Belum ada data peminjaman</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    
                    <!-- Hari Ini -->
                    <div class="bg-white/90 glass rounded-xl shadow-sm border border-gray-200/70 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-800">Hari Ini</h4>
                            <i class="fas fa-calendar-day text-gray-400"></i>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-primary-600 mb-1" x-text="stats.today_transactions">0</div>
                            <div class="text-sm text-gray-600">Peminjaman Baru</div>
                        </div>
                    </div>
                </div>
            </div>


    </main>

    <script>
        function dashboardApp() {
            return {
                currentDate: new Date().toLocaleDateString('id-ID', { 
                    weekday: 'long', 
                    day: 'numeric', 
                    month: 'long', 
                    year: 'numeric' 
                }),
                
                stats: @json($stats),
                
                init() {
                    // Jika ingin auto-refresh data setiap beberapa menit
                    setInterval(() => {
                        this.fetchDashboardData();
                    }, 300000); // 5 menit
                },
                
                fetchDashboardData() {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    
                    fetch('/admin/dashboard/data', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            ...(csrfToken && { 'X-CSRF-TOKEN': csrfToken.content })
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            this.stats = data.stats;
                        } else {
                            console.error('Dashboard data fetch failed:', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching dashboard data:', error);
                        // Don't show error to user for auto-refresh failures
                        if (!error.message.includes('failed to fetch')) {
                            // Optionally show a subtle notification here
                        }
                    });
                }
            }
        }



        // Toggle sidebar for mobile
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
        });
    </script>
</body>
</html>