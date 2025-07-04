<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - Inventaris Barang Sekolah</title>
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
        .badge-aktif {
            @apply bg-emerald-50 text-emerald-800 px-2.5 py-0.5 rounded-full text-xs font-medium;
        }
        .badge-perbaikan {
            @apply bg-amber-50 text-amber-800 px-2.5 py-0.5 rounded-full text-xs font-medium;
        }
        .badge-tidak_aktif {
            @apply bg-rose-50 text-rose-800 px-2.5 py-0.5 rounded-full text-xs font-medium;
        }
        .badge-inventaris {
            @apply bg-indigo-50 text-indigo-800 px-2.5 py-0.5 rounded-full text-xs font-medium;
        }
        .badge-transaksi {
            @apply bg-purple-50 text-purple-800 px-2.5 py-0.5 rounded-full text-xs font-medium;
        }
        .badge-ruangan {
            @apply bg-blue-50 text-blue-800 px-2.5 py-0.5 rounded-full text-xs font-medium;
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

<body class="font-sans antialiased bg-gradient-to-br from-gray-50 via-white to-gray-100">
    @include('partials.notification-system', [
        'clickUrl' => '/admin/transaksi',
        'actionText' => 'ke halaman transaksi'
    ])
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
                        <a href="/admin/laporan" class="flex items-center px-4 py-3 text-white bg-gradient-to-r from-primary to-secondary rounded-lg shadow-sm">
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
            <input type="text" class="w-full pl-10 pr-4 py-2 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-slow" placeholder="Cari...">
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
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Manajemen Laporan</h1>
                    <p class="text-gray-600">Kelola dan buat laporan inventaris sekolah</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <button class="bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white px-5 py-2.5 rounded-lg flex items-center space-x-2 transition-slow shadow-md hover:shadow-lg" onclick="showModal('modal-generate')">
                        <i class="fas fa-plus"></i>
                        <span>Generate Laporan</span>
                    </button>
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white/90 glass rounded-xl shadow-sm border border-gray-200/70 p-6 hover-scale">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Laporan</p>
                            <h3 class="text-2xl font-semibold text-gray-800 mt-1">{{ $stats['total_reports'] ?? 0 }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                            <i class="fas fa-file-alt text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white/90 glass rounded-xl shadow-sm border border-gray-200/70 p-6 hover-scale">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Laporan Bulan Ini</p>
                            <h3 class="text-2xl font-semibold text-gray-800 mt-1">{{ $stats['monthly_reports'] ?? 0 }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center text-purple-600">
                            <i class="fas fa-calendar-alt text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white/90 glass rounded-xl shadow-sm border border-gray-200/70 p-6 hover-scale">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Format Tersedia</p>
                            <h3 class="text-2xl font-semibold text-gray-800 mt-1">{{ $stats['available_formats'] ?? 0 }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                            <i class="fas fa-file-export text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

           <!-- Main Card -->
            <div class="bg-white/90 glass rounded-xl shadow-sm border border-gray-200/70 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Laporan</h3>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" 
                               id="search-input"
                               class="pl-10 pr-4 py-2 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-slow" 
                               placeholder="Cari laporan..."
                               oninput="handleSearch()">
                    </div>
                </div>
                
                <div class="p-6">
                    <!-- Filter Tabs -->
                    <div class="flex border-b border-gray-200 mb-6">
                        <button class="px-4 py-2 text-sm font-medium text-primary-600 border-b-2 border-primary-500" data-tab="all">
                            Semua
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700" data-tab="inventory">
                            Inventaris
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700" data-tab="transaction">
                            Transaksi
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700" data-tab="room">
                            Ruangan
                        </button>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto rounded-lg border border-gray-200/70 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200/70">
                            <thead class="bg-gray-50/80">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nama Laporan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Jenis</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tanggal Dibuat</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Format</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Dibuat Oleh</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200/70" id="table-body">
                                @forelse ($reports as $report)
                                <tr class="report-row table-row-hover transition-colors duration-150" data-type="{{ $report->report_type }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report->report_name ?? 'No Name' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($report->report_type == 'inventory')
                                            <span class="badge-inventaris">Inventaris</span>
                                        @elseif($report->report_type == 'transaction')
                                            <span class="badge-transaksi">Transaksi</span>
                                        @elseif($report->report_type == 'room')
                                            <span class="badge-ruangan">Ruangan</span>
                                        @else
                                            <span class="badge-aktif">{{ ucfirst($report->report_type) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $report->created_at ? $report->created_at->format('d M Y') : 'No Date' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ strtoupper($report->file_format ?? 'Unknown') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $report->user->name ?? 'User tidak ditemukan' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ asset('storage/'.$report->file_path) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 p-2 rounded-lg hover:bg-indigo-50 transition-slow"
                                               download>
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data laporan yang ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6 flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                           Menampilkan {{ $reports->count() }} laporan
                        </div>
                        <div class="flex space-x-2">
                            {{ $reports->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Generate Laporan -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" id="modal-generate" style="display: none;">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity transition-slow" onclick="hideModal('modal-generate')"></div>
            
            <div class="relative glass rounded-xl shadow-2xl border border-gray-200/70 w-full max-w-2xl transform transition-all">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Generate Laporan Baru</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-slow" onclick="hideModal('modal-generate')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="form-generate-laporan" action="{{ route('admin.laporan.generate') }}" method="POST" class="p-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="report_type" class="block text-sm font-medium text-gray-700 mb-1">Jenis Laporan</label>
                            <select name="report_type" id="report_type" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" required>
                                <option value="">Pilih Jenis Laporan</option>
                                <option value="inventory">Inventaris Barang</option>
                                <option value="transaction">Transaksi Peminjaman</option>
                                <option value="room">Inventaris per Ruangan</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                            <input type="date" name="date_from" id="date_from" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow">
                        </div>
                        
                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                            <input type="date" name="date_to" id="date_to" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow">
                        </div>
                        
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select name="category" id="category" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="room" class="block text-sm font-medium text-gray-700 mb-1">Ruangan</label>
                            <select name="room" id="room" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow">
                                <option value="">Semua Ruangan</option>
                                @foreach($ruangans as $ruangan)
                                    <option value="{{ $ruangan->id }}">{{ $ruangan->nama_ruangan }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="format" class="block text-sm font-medium text-gray-700 mb-1">Format</label>
                            <select name="format" id="format" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" required>
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow" onclick="hideModal('modal-generate')">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-lg hover:from-primary/90 hover:to-secondary/90 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow shadow-sm hover:shadow-md">
                            Generate Laporan
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

    // Modal functions
    function showModal(id) {
        document.getElementById(id).style.display = 'flex';
    }

    function hideModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    // Tab functionality
    document.querySelectorAll('[data-tab]').forEach(tab => {
        tab.addEventListener('click', function() {
            // Update active tab
            document.querySelectorAll('[data-tab]').forEach(t => {
                t.classList.remove('text-primary-600', 'border-primary-500');
                t.classList.add('text-gray-500', 'hover:text-gray-700');
            });
            
            this.classList.add('text-primary-600', 'border-primary-500');
            this.classList.remove('text-gray-500', 'hover:text-gray-700');
            
            // Filter reports
            const tabType = this.getAttribute('data-tab');
            const rows = document.querySelectorAll('.report-row');
            
            rows.forEach(row => {
                if (tabType === 'all') {
                    row.style.display = '';
                } else {
                    const reportType = row.getAttribute('data-type');
                    if (reportType === tabType) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        });
    });

    // Search functionality
    function handleSearch() {
        const searchTerm = document.getElementById('search-input').value.toLowerCase();
        const rows = document.querySelectorAll('.report-row');
        
        rows.forEach(row => {
            if (row.style.display === 'none') return;
            
            const reportName = row.cells[0].textContent.toLowerCase();
            const reportType = row.cells[1].textContent.toLowerCase();
            
            if (reportName.includes(searchTerm) || reportType.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Date validation
    document.getElementById('date_from').addEventListener('change', function() {
        const dateFrom = this.value;
        const dateToInput = document.getElementById('date_to');
        
        if (dateFrom) {
            dateToInput.min = dateFrom;
            
            // If current date_to is before new date_from, reset it
            if (dateToInput.value && new Date(dateToInput.value) < new Date(dateFrom)) {
                dateToInput.value = dateFrom;
            }
        }
    });

    document.getElementById('date_to').addEventListener('change', function() {
        const dateTo = this.value;
        const dateFromInput = document.getElementById('date_from');
        
        if (dateTo && dateFromInput.value && new Date(dateTo) < new Date(dateFromInput.value)) {
            alert('Tanggal akhir tidak boleh sebelum tanggal mulai');
            this.value = dateFromInput.value;
        }
    });

    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        // Set date to to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('date_to').value = today;
        document.getElementById('date_to').min = today;
        
        // Set date from to 30 days ago
        const thirtyDaysAgo = new Date();
        thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
        document.getElementById('date_from').value = thirtyDaysAgo.toISOString().split('T')[0];
        document.getElementById('date_from').max = today;
        
        // Form submission with notification
        document.getElementById('form-generate-laporan').addEventListener('submit', function(e) {
            const reportType = document.getElementById('report_type').value;
            const dateFrom = document.getElementById('date_from').value;
            const dateTo = document.getElementById('date_to').value;
            
            if (!reportType) {
                e.preventDefault();
                alert('Silakan pilih jenis laporan terlebih dahulu');
                return;
            }
            
            if (dateFrom && dateTo && new Date(dateFrom) > new Date(dateTo)) {
                e.preventDefault();
                alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
                document.getElementById('date_to').value = dateFrom;
                return;
            }

            // Show generating notification immediately
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating...';
            submitButton.disabled = true;
            
            // Hide modal immediately to not block download
            hideModal('modal-generate');
            
            // Show notification about download starting
            if (window.showNotification) {
                window.showNotification(
                    'info', 
                    'Laporan sedang digenerate. Download akan dimulai secara otomatis...', 
                    false
                );
            }
            
            // Reset button after short delay to allow download to start
            setTimeout(() => {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
                
                // Show success notification after download should have started
                if (window.showNotification) {
                    window.showNotification(
                        'success', 
                        'Laporan berhasil dibuat dan didownload!', 
                        true
                    );
                }
                
                // Refresh page to show new report in list after download
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }, 2000);
        });
    });
</script>
</body>
</html>