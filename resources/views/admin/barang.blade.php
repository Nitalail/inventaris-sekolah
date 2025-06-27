<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang - Inventaris Barang Sekolah</title>
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
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
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
        
        /* Smooth transitions */
        .transition-slow {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Badge styles */
        .badge {
            @apply px-2 py-1 text-xs font-medium rounded-full;
        }
        .badge-success {
            @apply bg-green-100 text-green-800;
        }
        .badge-warning {
            @apply bg-yellow-100 text-yellow-800;
        }
        .badge-danger {
            @apply bg-red-100 text-red-800;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gradient-to-br from-gray-50 via-white to-gray-100" x-data="barangData()">
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
                        <a href="/admin/barang" class="flex items-center px-4 py-3 text-white bg-gradient-to-r from-primary to-secondary rounded-lg shadow-sm">
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
            <input type="text" class="w-full pl-10 pr-4 py-2 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-slow" placeholder="Cari barang...">
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
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Manajemen Barang</h1>
                    <p class="text-gray-600">Kelola semua barang inventaris sekolah</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <button class="bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white px-5 py-2.5 rounded-lg flex items-center space-x-2 transition-slow shadow-md hover:shadow-lg" @click="showAddModal = true">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Barang</span>
                    </button>

                    <button class="bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white px-5 py-2.5 rounded-lg flex items-center space-x-2 transition-slow shadow-md hover:shadow-lg" @click="showAddSubModal = true">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Sub Barang</span>
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
            @if($errors->any()))
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

            <!-- Filter Section -->
            <div class="bg-white/90 glass rounded-xl shadow-sm border border-gray-200/70 overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-800">Filter Barang</h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.barang.index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                <select name="kategori_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow">
                                    <option value="">Semua Kategori</option>
                                    @foreach($kategori as $kat)
                                        <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                            {{ $kat->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ruangan</label>
                                <select name="ruangan_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow">
                                    <option value="">Semua Ruangan</option>
                                    @foreach($ruangan as $ruang)
                                        <option value="{{ $ruang->id }}" {{ request('ruangan_id') == $ruang->id ? 'selected' : '' }}>
                                            {{ $ruang->nama_ruangan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi</label>
                                <select name="kondisi" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow">
                                    <option value="">Semua Kondisi</option>
                                    <option value="baik" {{ request('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="rusak_ringan" {{ request('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                    <option value="rusak_berat" {{ request('kondisi') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                                <select name="tahun_perolehan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow">
                                    <option value="">Semua Tahun</option>
                                    @foreach($tahun_perolehan as $tahun)
                                        <option value="{{ $tahun }}" {{ request('tahun_perolehan') == $tahun ? 'selected' : '' }}>
                                            {{ $tahun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-end space-x-2">
                                <button type="submit" class="w-full bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white px-4 py-2 rounded-lg transition-slow shadow-sm hover:shadow-md">
                                    <i class="fas fa-filter mr-2"></i> Filter
                                </button>
                                <a href="{{ route('admin.barang.index') }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg transition-slow shadow-sm hover:shadow-md text-center">
                                    <i class="fas fa-sync-alt mr-2"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Barang Table -->
            <div class="bg-white/90 glass rounded-xl shadow-sm border border-gray-200/70 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Daftar Barang 
                        <span class="text-sm font-normal text-gray-500">({{ $barangs->total() }} barang)</span>
                    </h3>
                </div>
                
                <div class="p-6">
                    @if($barangs->count() > 0)
                    <div class="overflow-x-auto rounded-lg border border-gray-200/70 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200/70">
                            <thead class="bg-gray-50/80">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">No</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Kode</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nama Barang</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Kategori</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Kondisi</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Jumlah</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Ruangan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200/70">
                                @foreach($barangs as $barang)
                                <tr class="table-row-hover transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $barang->kode }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $barang->nama }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $barang->kategori->nama ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="badge {{ $barang->getKondisiBadgeClass() }}">
                                            {{ $barang->kondisi_name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $barang->jumlah }} {{ $barang->satuan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $barang->ruangan->nama_ruangan ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button class="text-indigo-600 hover:text-indigo-900 p-2 rounded-lg hover:bg-indigo-50 transition-slow"
                                                    @click="openViewModal({{ json_encode($barang) }})"
                                                    title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="text-indigo-600 hover:text-indigo-900 p-2 rounded-lg hover:bg-indigo-50 transition-slow"
                                                    @click="openEditModal({{ json_encode($barang) }})"
                                                    title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.barang.destroy', $barang->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-600 hover:text-rose-900 p-2 rounded-lg hover:bg-rose-50 transition-slow" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6 flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            Menampilkan {{ $barangs->firstItem() }} - {{ $barangs->lastItem() }} dari {{ $barangs->total() }} barang
                        </div>
                        <div class="flex space-x-2">
                            {{ $barangs->appends(request()->query())->links() }}
                        </div>
                    </div>
                    @else
                    <div class="text-center py-12">
                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-box-open text-3xl text-gray-400"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Tidak ada barang ditemukan</h4>
                        <p class="text-gray-500 mb-6">Belum ada data barang atau filter tidak menemukan hasil.</p>
                        @if(request()->hasAny(['kategori_id', 'ruangan_id', 'kondisi', 'tahun_perolehan']))
                            <a href="{{ route('admin.barang.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-lg hover:from-primary/90 hover:to-secondary/90 transition-slow shadow-sm hover:shadow-md">
                                <i class="fas fa-arrow-left mr-2"></i> Lihat Semua Barang
                            </a>
                        @else
                            <button class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-lg hover:from-primary/90 hover:to-secondary/90 transition-slow shadow-sm hover:shadow-md" @click="showAddModal = true">
                                <i class="fas fa-plus mr-2"></i> Tambah Barang
                            </button>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Modal Tambah Barang -->
                <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-show="showAddModal" x-cloak>
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity transition-slow" @click="showAddModal = false"></div>
                
                <div class="relative glass rounded-xl shadow-2xl border border-gray-200/70 w-full max-w-4xl h-auto max-h-[90vh] overflow-y-auto transform transition-all"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Tambah Barang Baru</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-slow" @click="showAddModal = false">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form method="POST" action="{{ route('admin.barang.store') }}" class="p-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="kondisi" class="block text-sm font-medium text-gray-700 mb-1">Barang <span class="text-red-500">*</span></label>
                            <select name="kondisi" id="kondisi" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" required>
                                <option value="">Lenovo i3</option>
                                <option value="baik">Kamera DSLR</option>
                            </select>
                        </div>
                        <div>
                            <label for="kode" class="block text-sm font-medium text-gray-700 mb-1">Kode Barang <span class="text-red-500">*</span></label>
                            <input type="text" name="kode" id="kode" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" placeholder="Masukkan kode barang" required>
                        </div>
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Barang <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="nama" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" placeholder="Masukkan nama barang" required>
                        </div>
                        <div>
                            <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                            <select name="kategori_id" id="kategori_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="kondisi" class="block text-sm font-medium text-gray-700 mb-1">Kondisi <span class="text-red-500">*</span></label>
                            <select name="kondisi" id="kondisi" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" required>
                                <option value="">Pilih Kondisi</option>
                                <option value="baik">Baik</option>
                                <option value="rusak_ringan">Rusak Ringan</option>
                                <option value="rusak_berat">Rusak Berat</option>
                            </select>
                        </div>
                        <div>
                            <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-1">Jumlah <span class="text-red-500">*</span></label>
                            <input type="number" name="jumlah" id="jumlah" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" placeholder="Masukkan jumlah" min="1" required>
                        </div>
                        <div>
                            <label for="satuan" class="block text-sm font-medium text-gray-700 mb-1">Satuan <span class="text-red-500">*</span></label>
                            <select name="satuan" id="satuan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" required>
                                <option value="">Pilih Satuan</option>
                                <option value="buah">Buah</option>
                                <option value="unit">Unit</option>
                                <option value="set">Set</option>
                                <option value="paket">Paket</option>
                                <option value="lembar">Lembar</option>
                                <option value="buku">Buku</option>
                                <option value="kotak">Kotak</option>
                                <option value="botol">Botol</option>
                                <option value="meter">Meter</option>
                                <option value="kilogram">Kilogram</option>
                                <option value="liter">Liter</option>
                            </select>
                        </div>
                        <div>
                            <label for="ruangan_id" class="block text-sm font-medium text-gray-700 mb-1">Ruangan <span class="text-red-500">*</span></label>
                            <select name="ruangan_id" id="ruangan_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" required>
                                <option value="">Pilih Ruangan</option>
                                @foreach ($ruangan as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_ruangan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="tahun_perolehan" class="block text-sm font-medium text-gray-700 mb-1">Tahun Perolehan <span class="text-red-500">*</span></label>
                            <input type="number" name="tahun_perolehan" id="tahun_perolehan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" placeholder="Masukkan tahun perolehan" min="1900" max="{{ date('Y') }}" required>
                        </div>
                        <div>
                            <label for="sumber_dana" class="block text-sm font-medium text-gray-700 mb-1">Sumber Dana <span class="text-red-500">*</span></label>
                            <select name="sumber_dana" id="sumber_dana" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" required>
                                <option value="">Pilih Sumber Dana</option>
                                <option value="BOS">BOS</option>
                                <option value="Komite">Komite</option>
                                <option value="Bantuan Pemerintah">Bantuan Pemerintah</option>
                                <option value="Hibah">Hibah</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" placeholder="Masukkan deskripsi barang (opsional)"></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow" @click="showAddModal = false">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-lg hover:from-primary/90 hover:to-secondary/90 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow shadow-sm hover:shadow-md">
                            <i class="fas fa-save mr-2"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Tambah Sub Barang -->
                <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-show="showAddSubModal" x-cloak>
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity transition-slow" @click="showAddSubModal = false"></div>
                
                <div class="relative glass rounded-xl shadow-2xl border border-gray-200/70 w-full max-w-4xl h-auto max-h-[90vh] overflow-y-auto transform transition-all"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Tambah Sub Barang Baru</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-slow" @click="showAddSubModal = false">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form method="POST" action="{{ route('admin.barang.store') }}" class="p-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="kondisi" class="block text-sm font-medium text-gray-700 mb-1">Barang <span class="text-red-500">*</span></label>
                            <select name="barang_id" id="barang_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" required>
                                <option value="">Pilih Barang</option>
                                @foreach($barangs as $barang)
                                    <option value="{{ $barang->id }}">{{ $barang->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="kode" class="block text-sm font-medium text-gray-700 mb-1">Kode Barang <span class="text-red-500">*</span></label>
                            <input type="text" name="kode" id="kode" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" placeholder="Masukkan kode barang" required>
                        </div>
                        
                        <div>
                            <label for="kondisi" class="block text-sm font-medium text-gray-700 mb-1">Kondisi <span class="text-red-500">*</span></label>
                            <select name="kondisi" id="kondisi" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" required>
                                <option value="">Pilih Kondisi</option>
                                <option value="baik">Baik</option>
                                <option value="rusak_ringan">Rusak Ringan</option>
                                <option value="rusak_berat">Rusak Berat</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="tahun_perolehan" class="block text-sm font-medium text-gray-700 mb-1">Tahun Perolehan <span class="text-red-500">*</span></label>
                            <input type="number" name="tahun_perolehan" id="tahun_perolehan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" placeholder="Masukkan tahun perolehan" min="1900" max="{{ date('Y') }}" required>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow" @click="showAddSubModal = false">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-lg hover:from-primary/90 hover:to-secondary/90 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow shadow-sm hover:shadow-md">
                            <i class="fas fa-save mr-2"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit Barang -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-show="showEditModal" x-cloak>
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity transition-slow" @click="showEditModal = false"></div>
            
            <div class="relative glass rounded-xl shadow-2xl border border-gray-200/70 w-full max-w-4xl h-auto max-h-[90vh] overflow-y-auto transform transition-all"                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Barang</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-slow" @click="showEditModal = false">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form method="POST" :action="'/admin/barang/' + editItem.id" class="p-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                                                       <label for="edit_kode" class="block text-sm font-medium text-gray-700 mb-1">Kode Barang <span class="text-red-500">*</span></label>
                            <input type="text" name="kode" id="edit_kode" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" 
                                x-model="editItem.kode" required>
                        </div>
                        <div>
                            <label for="edit_nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Barang <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="edit_nama" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" 
                                x-model="editItem.nama" required>
                        </div>
                        <div>
                            <label for="edit_kategori_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                            <select name="kategori_id" id="edit_kategori_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" 
                                x-model="editItem.kategori_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="edit_kondisi" class="block text-sm font-medium text-gray-700 mb-1">Kondisi <span class="text-red-500">*</span></label>
                            <select name="kondisi" id="edit_kondisi" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" 
                                x-model="editItem.kondisi" required>
                                <option value="">Pilih Kondisi</option>
                                <option value="baik">Baik</option>
                                <option value="rusak_ringan">Rusak Ringan</option>
                                <option value="rusak_berat">Rusak Berat</option>
                            </select>
                        </div>
                        <div>
                            <label for="edit_jumlah" class="block text-sm font-medium text-gray-700 mb-1">Jumlah <span class="text-red-500">*</span></label>
                            <input type="number" name="jumlah" id="edit_jumlah" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" 
                                x-model="editItem.jumlah" min="1" required>
                        </div>
                        <div>
                            <label for="edit_satuan" class="block text-sm font-medium text-gray-700 mb-1">Satuan <span class="text-red-500">*</span></label>
                            <select name="satuan" id="edit_satuan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" 
                                x-model="editItem.satuan" required>
                                <option value="">Pilih Satuan</option>
                                <option value="buah">Buah</option>
                                <option value="unit">Unit</option>
                                <option value="set">Set</option>
                                <option value="paket">Paket</option>
                                <option value="lembar">Lembar</option>
                                <option value="buku">Buku</option>
                                <option value="kotak">Kotak</option>
                                <option value="botol">Botol</option>
                                <option value="meter">Meter</option>
                                <option value="kilogram">Kilogram</option>
                                <option value="liter">Liter</option>
                            </select>
                        </div>
                        <div>
                            <label for="edit_ruangan_id" class="block text-sm font-medium text-gray-700 mb-1">Ruangan <span class="text-red-500">*</span></label>
                            <select name="ruangan_id" id="edit_ruangan_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" 
                                x-model="editItem.ruangan_id" required>
                                <option value="">Pilih Ruangan</option>
                                @foreach ($ruangan as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_ruangan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="edit_tahun_perolehan" class="block text-sm font-medium text-gray-700 mb-1">Tahun Perolehan <span class="text-red-500">*</span></label>
                            <input type="number" name="tahun_perolehan" id="edit_tahun_perolehan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" 
                                x-model="editItem.tahun_perolehan" min="1900" max="{{ date('Y') }}" required>
                        </div>
                        <div>
                            <label for="edit_sumber_dana" class="block text-sm font-medium text-gray-700 mb-1">Sumber Dana <span class="text-red-500">*</span></label>
                            <select name="sumber_dana" id="edit_sumber_dana" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" 
                                x-model="editItem.sumber_dana" required>
                                <option value="">Pilih Sumber Dana</option>
                                <option value="BOS">BOS</option>
                                <option value="Komite">Komite</option>
                                <option value="Bantuan Pemerintah">Bantuan Pemerintah</option>
                                <option value="Hibah">Hibah</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label for="edit_deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="deskripsi" id="edit_deskripsi" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" 
                                x-model="editItem.deskripsi" placeholder="Masukkan deskripsi barang (opsional)"></textarea>
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

        <!-- Modal View Barang -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-show="showViewModal" x-cloak>
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity transition-slow" @click="showViewModal = false"></div>
            
            <div class="relative glass rounded-xl shadow-2xl border border-gray-200/70 w-full max-w-2xl transform transition-all"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Detail Barang</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-slow" @click="showViewModal = false">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kolom Kiri -->
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-2 border-b pb-1">Informasi Barang</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Kode Barang:</span>
                                        <span class="text-sm font-medium text-gray-900" x-text="viewItem.kode"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Nama Barang:</span>
                                        <span class="text-sm font-medium text-gray-900" x-text="viewItem.nama"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Kategori:</span>
                                        <span class="text-sm font-medium text-gray-900" x-text="viewItem.kategori?.nama || '-'"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Jumlah:</span>
                                        <span class="text-sm font-medium text-gray-900" x-text="`${viewItem.jumlah} ${viewItem.satuan}`"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-2 border-b pb-1">Kondisi</h4>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Status:</span>
                                    <span class="badge" 
                                          :class="{
                                              'badge-success': viewItem.kondisi === 'baik',
                                              'badge-warning': viewItem.kondisi === 'rusak_ringan',
                                              'badge-danger': viewItem.kondisi === 'rusak_berat'
                                          }"
                                          x-text="viewItem.kondisi === 'baik' ? 'Baik' : 
                                                 viewItem.kondisi === 'rusak_ringan' ? 'Rusak Ringan' : 
                                                 'Rusak Berat'">
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Kolom Kanan -->
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-2 border-b pb-1">Lokasi & Perolehan</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Ruangan:</span>
                                        <span class="text-sm font-medium text-gray-900" x-text="viewItem.ruangan?.nama_ruangan || '-'"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Tahun Perolehan:</span>
                                        <span class="text-sm font-medium text-gray-900" x-text="viewItem.tahun_perolehan"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Sumber Dana:</span>
                                        <span class="text-sm font-medium text-gray-900" x-text="viewItem.sumber_dana || '-'"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-2 border-b pb-1">Informasi Sistem</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Dibuat Pada:</span>
                                        <span class="text-sm font-medium text-gray-900" x-text="new Date(viewItem.created_at).toLocaleString('id-ID')"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Terakhir Diupdate:</span>
                                        <span class="text-sm font-medium text-gray-900" x-text="new Date(viewItem.updated_at).toLocaleString('id-ID')"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Deskripsi (Full Width) -->
                        <div class="md:col-span-2">
                            <h4 class="text-sm font-medium text-gray-500 mb-2 border-b pb-1">Deskripsi</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-700" x-text="viewItem.deskripsi || 'Tidak ada deskripsi'"></p>
                            </div>
                        </div>

                        <div class="overflow-x-auto rounded-lg border border-gray-200/70 shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200/70">
                                <thead class="bg-gray-50/80">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">No</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Kode</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nama Barang</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Kategori</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Kondisi</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Jumlah</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Ruangan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-gray-200/70">
                                    @foreach($barangs as $barang)
                                    <tr class="table-row-hover transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $barang->kode }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $barang->nama }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $barang->kategori->nama ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="badge {{ $barang->getKondisiBadgeClass() }}">
                                                {{ $barang->kondisi_name }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $barang->jumlah }} {{ $barang->satuan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $barang->ruangan->nama_ruangan ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button class="text-indigo-600 hover:text-indigo-900 p-2 rounded-lg hover:bg-indigo-50 transition-slow"
                                                        @click="openViewModal({{ json_encode($barang) }})"
                                                        title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="text-indigo-600 hover:text-indigo-900 p-2 rounded-lg hover:bg-indigo-50 transition-slow"
                                                        @click="openEditModal({{ json_encode($barang) }})"
                                                        title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('admin.barang.destroy', $barang->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-600 hover:text-rose-900 p-2 rounded-lg hover:bg-rose-50 transition-slow" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200/70 flex justify-end space-x-3">
                    <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow" @click="showViewModal = false">
                        Tutup
                    </button>
                    <button type="button" class="px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-lg hover:from-primary/90 hover:to-secondary/90 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow shadow-sm hover:shadow-md" 
                            @click="showViewModal = false; openEditModal(viewItem)">
                        <i class="fas fa-edit mr-2"></i> Edit Barang
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Hapus Barang -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-show="showDeleteModal" x-cloak>
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity transition-slow" @click="showDeleteModal = false"></div>
            
            <div class="relative glass rounded-xl shadow-2xl border border-gray-200/70 w-full max-w-md transform transition-all"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Konfirmasi Hapus</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-slow" @click="showDeleteModal = false">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="p-6">
                    <p class="text-gray-700">Apakah Anda yakin ingin menghapus barang ini?</p>
                    <div class="mt-4 bg-gray-50 p-4 rounded-lg">
                        <p class="font-medium text-gray-900" x-text="`${deleteItem.kode} - ${deleteItem.nama}`"></p>
                    </div>
                    <p class="mt-4 text-sm text-red-600 font-medium">Tindakan ini tidak dapat dibatalkan!</p>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200/70 flex justify-end space-x-3">
                    <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow" @click="showDeleteModal = false">
                        Batal
                    </button>
                    <form :action="'/admin/barang/' + deleteItem.id" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-red-500 to-rose-500 text-white rounded-lg hover:from-red-600 hover:to-rose-600 focus:outline-none focus:ring-2 focus:ring-red-500 transition-slow shadow-sm hover:shadow-md">
                            <i class="fas fa-trash mr-2"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        function barangData() {
            return {
                showAddModal: false,
                showAddSubModal: false,
                showEditModal: false,
                showViewModal: false,
                showDeleteModal: false,
                editItem: {
                    id: '',
                    kode: '',
                    nama: '',
                    kategori_id: '',
                    kondisi: '',
                    jumlah: '',
                    satuan: '',
                    ruangan_id: '',
                    tahun_perolehan: '',
                    sumber_dana: '',
                    deskripsi: ''
                },
                viewItem: {},
                deleteItem: {
                    id: '',
                    kode: '',
                    nama: ''
                },
                
                openEditModal(item) {
                    this.editItem = {
                        id: item.id,
                        kode: item.kode,
                        nama: item.nama,
                        kategori_id: item.kategori_id,
                        kondisi: item.kondisi,
                        jumlah: item.jumlah,
                        satuan: item.satuan,
                        ruangan_id: item.ruangan_id,
                        tahun_perolehan: item.tahun_perolehan,
                        sumber_dana: item.sumber_dana,
                        deskripsi: item.deskripsi
                    };
                    this.showEditModal = true;
                },
                
                openViewModal(item) {
                    this.viewItem = item;
                    this.showViewModal = true;
                },
                
                openDeleteModal(item) {
                    this.deleteItem = {
                        id: item.id,
                        kode: item.kode,
                        nama: item.nama
                    };
                    this.showDeleteModal = true;
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