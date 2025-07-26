<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruangan - Inventaris Barang Sekolah</title>
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
                        <a href="/admin/ruangan" class="flex items-center px-4 py-3 text-white bg-gradient-to-r from-primary to-secondary rounded-lg shadow-sm">
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
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Manajemen Ruangan</h1>
                    <p class="text-gray-600">Kelola ruangan dan informasi terkait</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <button class="bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white px-5 py-2.5 rounded-lg flex items-center space-x-2 transition-slow shadow-md hover:shadow-lg" onclick="showModal('modal-tambah')">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Ruangan</span>
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

            <!-- Error Alert for session errors -->
            @if(session('error'))
            <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm flex items-start" x-data="{ show: true }" x-show="show" @click.away="show = false" style="cursor: pointer;">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-gradient-to-r from-red-500 to-rose-500 rounded-full flex items-center justify-center text-white">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <h4 class="text-sm font-medium text-red-800">Gagal!</h4>
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
                <button class="ml-3 text-red-700 hover:text-red-900 transition-slow" @click="show = false">
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

           <!-- Main Card -->
            <div class="bg-white/90 glass rounded-xl shadow-sm border border-gray-200/70 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Ruangan</h3>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" 
                               id="search-input"
                               class="pl-10 pr-4 py-2 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-slow" 
                               placeholder="Cari ruangan..."
                               oninput="handleSearch()">
                    </div>
                </div>
                
                <div class="p-6">
                    <!-- Table -->
                    <div class="overflow-x-auto rounded-lg border border-gray-200/70 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200/70">
                            <thead class="bg-gray-50/80">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">No</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Kode</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nama Ruangan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Jumlah Barang</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200/70" id="table-body">
                                @forelse ($rooms as $room)
                                <tr class="room-row table-row-hover transition-colors duration-150" data-item-count="{{ $room->total_sub_barang ?? 0 }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ($rooms->currentPage() - 1) * $rooms->perPage() + $loop->iteration }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 room-code">{{ $room->kode_ruangan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 room-name">{{ $room->nama_ruangan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $room->total_sub_barang ?? 0 }} barang</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if ($room->status === 'aktif')
                                            <span class="badge-aktif">Aktif</span>
                                        @elseif ($room->status === 'perbaikan')
                                            <span class="badge-perbaikan">Perbaikan</span>
                                        @else
                                            <span class="badge-tidak_aktif">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button class="text-indigo-600 hover:text-indigo-900 p-2 rounded-lg hover:bg-indigo-50 transition-slow"
                                                onclick='showDetailModal(@json($room))'
                                                type="button">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="text-amber-600 hover:text-amber-900 p-2 rounded-lg hover:bg-amber-50 transition-slow"
                                                onclick="openEditModal({{ json_encode($room) }})"
                                                type="button">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data ruangan yang ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6 flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                           Menampilkan {{ $rooms->count() }} ruangan
                        </div>
                        <div class="flex space-x-2">
                            {{ $rooms->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Ruangan -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" id="modal-tambah" style="display: none;">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity transition-slow" onclick="hideModal('modal-tambah')"></div>
            
            <div class="relative glass rounded-xl shadow-2xl border border-gray-200/70 w-full max-w-md transform transition-all">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Tambah Ruangan Baru</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-slow" onclick="hideModal('modal-tambah')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="form-tambah-ruangan" action="/admin/ruangan" method="POST" class="p-6">
                    @csrf
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="kode_ruangan" class="block text-sm font-medium text-gray-700 mb-1">Kode Ruangan</label>
                            <input type="text" name="kode_ruangan" id="kode_ruangan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" 
                                placeholder="R-001" required>
                        </div>
                        <div>
                            <label for="nama_ruangan" class="block text-sm font-medium text-gray-700 mb-1">Nama Ruangan</label>
                            <input type="text" name="nama_ruangan" id="nama_ruangan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" 
                                placeholder="Ruangan Kelas" required>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" required>
                            <option value="aktif">Aktif</option>
                            <option value="perbaikan">Perbaikan</option>
                            <option value="tidak_aktif">Tidak Aktif</option>
                        </select>
                    </div>
                    
                    <div class="mt-4">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" 
                            placeholder="Deskripsi ruangan..."></textarea>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow" onclick="hideModal('modal-tambah')">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-lg hover:from-primary/90 hover:to-secondary/90 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow shadow-sm hover:shadow-md">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Detail Ruangan -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" id="modal-lihat" style="display: none;">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity transition-slow" onclick="hideModal('modal-lihat')"></div>
            
            <div class="relative glass rounded-xl shadow-2xl border border-gray-200/70 w-full max-w-md transform transition-all">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Detail Ruangan</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-slow" onclick="hideModal('modal-lihat')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="p-6">
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-500">Kode Ruangan</h4>
                        <p class="mt-1 text-sm text-gray-900" id="detail_kode">-</p>
                    </div>
                    
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-500">Nama Ruangan</h4>
                        <p class="mt-1 text-sm text-gray-900" id="detail_nama">-</p>
                    </div>
                    
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-500">Jumlah Barang</h4>
                        <p class="mt-1 text-sm text-gray-900" id="detail_jumlah_barang">-</p>
                    </div>
                    
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-500">Status</h4>
                        <p class="mt-1 text-sm" id="detail_status">-</p>
                    </div>
                    
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-500">Deskripsi</h4>
                        <p class="mt-1 text-sm text-gray-900" id="detail_deskripsi">-</p>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200/70 flex justify-end">
                    <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow" onclick="hideModal('modal-lihat')">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Edit Ruangan -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" id="modal-edit" style="display: none;">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity transition-slow" onclick="hideModal('modal-edit')"></div>
            
            <div class="relative glass rounded-xl shadow-2xl border border-gray-200/70 w-full max-w-md transform transition-all">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Ruangan</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-slow" onclick="hideModal('modal-edit')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="form-edit-ruangan" method="POST" class="p-6">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_id_ruangan" name="id">
                    
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="edit_kode_ruangan" class="block text-sm font-medium text-gray-700 mb-1">Kode Ruangan</label>
                            <input type="text" name="kode_ruangan" id="edit_kode_ruangan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow bg-gray-100 cursor-not-allowed" required readonly>
                        </div>
                        <div>
                            <label for="edit_nama_ruangan" class="block text-sm font-medium text-gray-700 mb-1">Nama Ruangan</label>
                            <input type="text" name="nama_ruangan" id="edit_nama_ruangan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" required>
                        </div>
                    </div>
                    

                    
                    <div class="mt-4">
                        <label for="edit_status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="edit_status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" required>
                            <option value="aktif">Aktif</option>
                            <option value="perbaikan">Perbaikan</option>
                            <option value="tidak_aktif">Tidak Aktif</option>
                        </select>
                    </div>
                    
                    <div class="mt-4">
                        <label for="edit_deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" id="edit_deskripsi" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"></textarea>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow" onclick="hideModal('modal-edit')">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-lg hover:from-primary/90 hover:to-secondary/90 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow shadow-sm hover:shadow-md">
                            Simpan Perubahan
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

        // Show detail modal with data
        function showDetailModal(data) {
            document.getElementById('detail_kode').textContent = data.kode_ruangan || '-';
            document.getElementById('detail_nama').textContent = data.nama_ruangan || '-';
            document.getElementById('detail_jumlah_barang').textContent = (data.total_sub_barang || 0) + ' barang';
            
            // Set status with appropriate badge
            const statusEl = document.getElementById('detail_status');
            statusEl.innerHTML = '';
            const statusBadge = document.createElement('span');
            
            if (data.status === 'aktif') {
                statusBadge.className = 'badge-aktif';
                statusBadge.textContent = 'Aktif';
            } else if (data.status === 'perbaikan') {
                statusBadge.className = 'badge-perbaikan';
                statusBadge.textContent = 'Perbaikan';
            } else {
                statusBadge.className = 'badge-tidak_aktif';
                statusBadge.textContent = 'Tidak Aktif';
            }
            
            statusEl.appendChild(statusBadge);
            document.getElementById('detail_deskripsi').textContent = data.deskripsi || '-';
            
            showModal('modal-lihat');
        }

        // Open edit modal with data
        function openEditModal(room) {
            const form = document.getElementById('form-edit-ruangan');
            form.action = `/admin/ruangan/${room.id}`;

            document.getElementById('edit_id_ruangan').value = room.id || '';
            document.getElementById('edit_kode_ruangan').value = room.kode_ruangan || '';
            document.getElementById('edit_nama_ruangan').value = room.nama_ruangan || '';
            document.getElementById('edit_status').value = room.status || 'aktif';
            document.getElementById('edit_deskripsi').value = room.deskripsi || '';

            // Check if room has items and disable "tidak_aktif" option if it does
            const statusSelect = document.getElementById('edit_status');
            const tidakAktifOption = statusSelect.querySelector('option[value="tidak_aktif"]');
            const itemCount = parseInt(room.total_sub_barang || 0);
            
            if (itemCount > 0) {
                // Disable "tidak_aktif" option if room has items
                tidakAktifOption.disabled = true;
                tidakAktifOption.textContent = 'Tidak Aktif (tidak tersedia - ruangan masih digunakan)';
                
                // If current status is "tidak_aktif" but room has items, change to "aktif"
                if (room.status === 'tidak_aktif') {
                    statusSelect.value = 'aktif';
                }
            } else {
                // Enable "tidak_aktif" option if room has no items
                tidakAktifOption.disabled = false;
                tidakAktifOption.textContent = 'Tidak Aktif';
            }

            showModal('modal-edit');
        }

        // Search functionality
        function handleSearch() {
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            const rows = document.querySelectorAll('.room-row');
            
            rows.forEach(row => {
                const namaRuangan = row.querySelector('.room-name').textContent.toLowerCase();
                const kodeRuangan = row.querySelector('.room-code').textContent.toLowerCase();
                
                                if (namaRuangan.includes(searchTerm) || kodeRuangan.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Initialize tooltips
        function initTooltips() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        // Initialize popovers
        function initPopovers() {
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initTooltips();
            initPopovers();
            
            // Add smooth scroll to all links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });

            // Auto-generate kode ruangan based on nama ruangan and random number
            const namaInput = document.getElementById('nama_ruangan');
            const kodeInput = document.getElementById('kode_ruangan');
            if (namaInput && kodeInput) {
                namaInput.addEventListener('input', function() {
                    let nama = namaInput.value.trim().toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9\-]/g, '');
                    if (nama.length > 0) {
                        let randomNum = Math.floor(100 + Math.random() * 900); // 3 digit random number
                        kodeInput.value = nama + '-' + randomNum;
                    } else {
                        kodeInput.value = '';
                    }
                });
            }
        });
    </script>
</body>
</html>