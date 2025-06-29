<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Pengguna - Inventaris Barang Sekolah</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
    </style>
</head>

<body class="font-sans antialiased bg-gradient-to-br from-gray-50 via-white to-gray-100" x-data="userManagement()">
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
                        <a href="/admin/pengguna" class="flex items-center px-4 py-3 text-white bg-gradient-to-r from-primary to-secondary rounded-lg shadow-sm">
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
            <input type="text" class="w-full pl-10 pr-4 py-2 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-slow" placeholder="Cari pengguna...">
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
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Manajemen Pengguna</h1>
                    <p class="text-gray-600">Kelola semua pengguna sistem inventaris sekolah</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <button class="bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white px-5 py-2.5 rounded-lg flex items-center space-x-2 transition-slow shadow-md hover:shadow-lg" @click="openAddModal()">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Pengguna</span>
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

            <!-- Filter Section -->
            <div class="bg-white/90 glass rounded-xl shadow-sm border border-gray-200/70 overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-800">Filter Pengguna</h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.pengguna.index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                                <input type="text" name="name" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" 
                                    placeholder="Cari berdasarkan nama..."
                                    value="{{ request('name') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Peran</label>
                                <select name="role" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow">
                                    <option value="">Semua Peran</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                                    <option value="pengguna" {{ request('role') == 'pengguna' ? 'selected' : '' }}>Pengguna</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="w-full bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white px-4 py-2 rounded-lg transition-slow shadow-sm hover:shadow-md">
                                    <i class="fas fa-search mr-2"></i> Filter
                                </button>
                            </div>
                            <div class="flex items-end">
                                <a href="{{ route('admin.pengguna.index') }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg transition-slow shadow-sm hover:shadow-md text-center">
                                    <i class="fas fa-times mr-2"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Users Table -->
            <div class="bg-white/90 glass rounded-xl shadow-sm border border-gray-200/70 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Daftar Pengguna 
                        @if(isset($users))
                            <span class="text-sm font-normal text-gray-500">({{ $users->total() }} pengguna)</span>
                        @endif
                    </h3>
                </div>
                
                <div class="p-6">
                    @if(isset($users) && $users->count() > 0)
                    <div class="overflow-x-auto rounded-lg border border-gray-200/70 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200/70">
                            <thead class="bg-gray-50/80">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nama</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Peran</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tanggal Dibuat</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200/70">
                                @foreach($users as $user)
                                <tr class="table-row-hover transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#USR-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $user->role == 'admin' ? 'bg-indigo-100 text-indigo-800' : 'bg-purple-100 text-purple-800' }}">
                                            {{ $user->role == 'admin' ? 'Administrator' : 'Pengguna' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button class="text-indigo-600 hover:text-indigo-900 p-2 rounded-lg hover:bg-indigo-50 transition-slow"
                                                @click="editUser({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ $user->role }}')"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="text-rose-600 hover:text-rose-900 p-2 rounded-lg hover:bg-rose-50 transition-slow"
                                                @click="deleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                                title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
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
                            Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} pengguna
                        </div>
                        <div class="flex space-x-2">
                            {{ $users->appends(request()->query())->links() }}
                        </div>
                    </div>
                    @elseif(isset($users))
                    <div class="text-center py-12">
                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-users text-3xl text-gray-400"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Tidak ada pengguna ditemukan</h4>
                        <p class="text-gray-500 mb-6">Belum ada data pengguna atau filter tidak menemukan hasil.</p>
                        @if(request()->hasAny(['name', 'role']))
                            <a href="{{ route('admin.pengguna.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-lg hover:from-primary/90 hover:to-secondary/90 transition-slow shadow-sm hover:shadow-md">
                                <i class="fas fa-arrow-left mr-2"></i> Lihat Semua Pengguna
                            </a>
                        @endif
                    </div>
                    @else
                    <div class="text-center py-12">
                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-users text-3xl text-gray-400"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Belum ada pengguna</h4>
                        <p class="text-gray-500 mb-6">Mulai dengan menambahkan pengguna pertama.</p>
                        <button class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-lg hover:from-primary/90 hover:to-secondary/90 transition-slow shadow-sm hover:shadow-md" @click="openAddModal()">
                            <i class="fas fa-plus mr-2"></i> Tambah Pengguna
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Modal Tambah Pengguna -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-show="showAddModal" x-cloak>
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity transition-slow" @click="closeModal('add')"></div>
            
            <div class="relative glass rounded-xl shadow-2xl border border-gray-200/70 w-full max-w-md transform transition-all"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Tambah Pengguna Baru</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-slow" @click="closeModal('add')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form method="POST" action="{{ route('admin.pengguna.store') }}" class="p-6">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" placeholder="Masukkan email" required>
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password" id="password" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" placeholder="Masukkan password" required>
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" placeholder="Konfirmasi password" required>
                        </div>
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Peran <span class="text-red-500">*</span></label>
                            <select name="role" id="role" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" required>
                                <option value="">Pilih Peran</option>
                                <option value="admin">Administrator</option>
                                <option value="pengguna">Pengguna</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow" @click="closeModal('add')">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-lg hover:from-primary/90 hover:to-secondary/90 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow shadow-sm hover:shadow-md">
                            <i class="fas fa-save mr-2"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit Pengguna -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-show="showEditModal" x-cloak>
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity transition-slow" @click="closeModal('edit')"></div>
            
            <div class="relative glass rounded-xl shadow-2xl border border-gray-200/70 w-full max-w-md transform transition-all"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Pengguna</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-slow" @click="closeModal('edit')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form method="POST" :action="'/admin/pengguna/' + selectedUserId" @submit.prevent="submitEditForm" class="p-6">
                    @method('PUT')
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="edit_name" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" 
                                x-model="selectedUserData.name" required>
                        </div>
                        <div>
                            <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="edit_email" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" 
                                x-model="selectedUserData.email" required>
                        </div>
                        <div>
                            <label for="edit_password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                            <input type="password" name="password" id="edit_password" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" 
                                placeholder="Kosongkan jika tidak ingin mengubah">
                        </div>
                        <div>
                            <label for="edit_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="edit_password_confirmation" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" 
                                placeholder="Konfirmasi password baru">
                        </div>
                        <div>
                            <label for="edit_role" class="block text-sm font-medium text-gray-700 mb-1">Peran <span class="text-red-500">*</span></label>
                            <select name="role" id="edit_role" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" 
                                x-model="selectedUserData.role" required>
                                <option value="admin">Administrator</option>
                                <option value="pengguna">Pengguna</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow" @click="closeModal('edit')">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-lg hover:from-primary/90 hover:to-secondary/90 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow shadow-sm hover:shadow-md">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Hapus Pengguna -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-show="showDeleteModal" x-cloak>
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity transition-slow" @click="closeModal('delete')"></div>
            
            <div class="relative glass rounded-xl shadow-2xl border border-gray-200/70 w-full max-w-md transform transition-all"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Konfirmasi Hapus</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-slow" @click="closeModal('delete')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form method="POST" :action="'/admin/pengguna/' + selectedUserId" class="p-6">
                    @method('DELETE')
                    @csrf
                    <div class="space-y-4">
                        <p>Apakah Anda yakin ingin menghapus pengguna: <strong class="font-medium" x-text="selectedUserName"></strong>?</p>
                        <p class="text-red-600 font-medium">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                                        <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow" @click="closeModal('delete')">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-red-500 to-rose-500 text-white rounded-lg hover:from-red-600 hover:to-rose-600 focus:outline-none focus:ring-2 focus:ring-red-500 transition-slow shadow-sm hover:shadow-md">
                            <i class="fas fa-trash mr-2"></i> Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function userManagement() {
            return {
                sidebarOpen: false,
                showAddModal: false,
                showEditModal: false,
                showDeleteModal: false,
                selectedUserId: null,
                selectedUserName: '',
                selectedUserData: {
                    name: '',
                    email: '',
                    role: 'pengguna'
                },
                
                openAddModal() {
                    this.showAddModal = true;
                },
                
                editUser(id, name, email, role) {
                    this.selectedUserId = id;
                    this.selectedUserData = {
                        name: name,
                        email: email,
                        role: role || 'pengguna'
                    };
                    this.showEditModal = true;
                },

                deleteUser(id, name) {
                    this.selectedUserId = id;
                    this.selectedUserName = name;
                    this.showDeleteModal = true;
                },
                
                closeModal(type) {
                    switch(type) {
                        case 'add':
                            this.showAddModal = false;
                            break;
                        case 'edit':
                            this.showEditModal = false;
                            break;
                        case 'delete':
                            this.showDeleteModal = false;
                            break;
                    }
                    this.selectedUserId = null;
                    this.selectedUserName = '';
                    this.selectedUserData = {
                        name: '',
                        email: '',
                        role: 'pengguna'
                    };
                },
                
                submitEditForm(event) {
                    event.preventDefault();
                    const form = event.target;
                    const formData = new FormData(form);
                    
                    // Add method override for PUT request
                    formData.append('_method', 'PUT');
                    
                    // Get CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(response => {
                        return response.json().then(data => {
                            if (!response.ok) {
                                // Handle validation errors
                                if (response.status === 422 && data.errors) {
                                    let errorMessages = [];
                                    for (let field in data.errors) {
                                        errorMessages.push(`${field}: ${data.errors[field].join(', ')}`);
                                    }
                                    throw new Error('Validasi gagal:\n' + errorMessages.join('\n'));
                                }
                                throw new Error(data.message || `Server error: ${response.status}`);
                            }
                            return data;
                        });
                    })
                    .then(data => {
                        console.log('Success:', data);
                        // Show success message
                        alert('Data pengguna berhasil diperbarui!');
                        // Close modal
                        this.closeModal('edit');
                        // Reload page to show changes
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menyimpan data:\n' + error.message);
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