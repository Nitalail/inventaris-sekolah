<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        .badge-gray {
            @apply bg-gray-100 text-gray-700;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gradient-to-br from-gray-50 via-white to-gray-100" x-data="barangData()">
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
                            class="flex items-center px-4 py-3 text-white bg-gradient-to-r from-primary to-secondary rounded-lg shadow-sm">
                            <i class="fas fa-box w-5 mr-3 text-center"></i>
                            <span>Barang</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/transaksi"
                            class="flex items-center px-4 py-3 text-gray-600 hover:text-primary-600 hover:bg-gray-100/50 rounded-lg transition-slow">
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
                placeholder="Cari barang...">
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
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Manajemen Barang</h1>
                    <p class="text-gray-600">Kelola semua barang inventaris sekolah</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <button
                        class="bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white px-5 py-2.5 rounded-lg flex items-center space-x-2 transition-slow shadow-md hover:shadow-lg"
                        @click="showAddModal = true">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Barang</span>
                    </button>
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

            <!-- Filter Section -->
            <div class="bg-white/90 glass rounded-xl shadow-sm border border-gray-200/70 overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-800">Filter Barang</h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.barang.index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                <select name="kategori_id"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($kategori as $kat)
                                        <option value="{{ $kat->id }}"
                                            {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                            {{ $kat->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ruangan</label>
                                <select name="ruangan_id"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow">
                                    <option value="">Semua Ruangan</option>
                                    @foreach ($ruangan as $ruang)
                                        <option value="{{ $ruang->id }}"
                                            {{ request('ruangan_id') == $ruang->id ? 'selected' : '' }}>
                                            {{ $ruang->nama_ruangan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-end space-x-2">
                                <button type="submit"
                                    class="w-full bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white px-4 py-2 rounded-lg transition-slow shadow-sm hover:shadow-md">
                                    <i class="fas fa-filter mr-2"></i> Filter
                                </button>
                                <a href="{{ route('admin.barang.index') }}"
                                    class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg transition-slow shadow-sm hover:shadow-md text-center">
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
                    @if ($barangs->count() > 0)
                        <div class="overflow-x-auto rounded-lg border border-gray-200/70 shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200/70">
                                <thead class="bg-gray-50/80">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            No</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Kode</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Nama Barang</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Kategori</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Satuan</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Ruangan</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200/70">
                                    @foreach ($barangs as $barang)
                                        <tr class="table-row-hover transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $loop->iteration }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $barang->kode }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $barang->nama }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $barang->kategori->nama ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $barang->satuan }}
                                                @if ($barang->sub_barang_count > 0)
                                                    <span
                                                        class="text-xs text-primary-600 ml-1">({{ $barang->sub_barang_count }}
                                                        sub)</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $barang->ruangan->nama_ruangan ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <button
                                                        class="text-indigo-600 hover:text-indigo-900 p-2 rounded-lg hover:bg-indigo-50 transition-slow"
                                                        @click="openViewModal({{ json_encode($barang) }})"
                                                        title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button
                                                        class="text-indigo-600 hover:text-indigo-900 p-2 rounded-lg hover:bg-indigo-50 transition-slow"
                                                        @click="openEditModal({{ json_encode($barang) }})"
                                                        title="Edit">
                                                        <i class="fas fa-edit"></i>
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
                                Menampilkan {{ $barangs->firstItem() }} - {{ $barangs->lastItem() }} dari
                                {{ $barangs->total() }} barang
                            </div>
                            <div class="flex space-x-2">
                                {{ $barangs->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div
                                class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-box-open text-3xl text-gray-400"></i>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Tidak ada barang ditemukan</h4>
                            <p class="text-gray-500 mb-6">Belum ada data barang atau filter tidak menemukan hasil.</p>
                            @if (request()->hasAny(['kategori_id', 'ruangan_id']))
                                <a href="{{ route('admin.barang.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-lg hover:from-primary/90 hover:to-secondary/90 transition-slow shadow-sm hover:shadow-md">
                                    <i class="fas fa-arrow-left mr-2"></i> Lihat Semua Barang
                                </a>
                            @else
                                <button
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-lg hover:from-primary/90 hover:to-secondary/90 transition-slow shadow-sm hover:shadow-md"
                                    @click="showAddModal = true">
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
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity transition-slow"
                @click="showAddModal = false"></div>

            <div class="relative glass rounded-xl shadow-2xl border border-gray-200/70 w-full max-w-4xl h-auto max-h-[90vh] overflow-y-auto transform transition-all"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Tambah Barang Baru</h3>
                    <button type="button"
                        class="text-gray-400 hover:text-gray-500 focus:outline-none transition-slow"
                        @click="showAddModal = false">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.barang.store') }}" class="p-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="kode" class="block text-sm font-medium text-gray-700 mb-1">Kode Barang
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="kode" id="kode"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow bg-gray-100 cursor-not-allowed"
                                placeholder="Masukkan kode barang" required readonly>
                        </div>
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Barang
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="nama"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                placeholder="Masukkan nama barang" required>
                        </div>
                        <div>
                            <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori
                                <span class="text-red-500">*</span></label>
                            <select name="kategori_id" id="kategori_id"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    
                        <div>
                            <label for="satuan" class="block text-sm font-medium text-gray-700 mb-1">Satuan <span
                                    class="text-red-500">*</span></label>
                            <select name="satuan" id="satuan"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                required>
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
                            <label for="ruangan_id" class="block text-sm font-medium text-gray-700 mb-1">Ruangan <span
                                    class="text-red-500">*</span></label>
                            <select name="ruangan_id" id="ruangan_id"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                required>
                                <option value="">Pilih Ruangan</option>
                                @foreach ($ruangan as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_ruangan }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="sumber_dana" class="block text-sm font-medium text-gray-700 mb-1">Sumber Dana
                                <span class="text-red-500">*</span></label>
                            <select name="sumber_dana" id="sumber_dana"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                required>
                                <option value="">Pilih Sumber Dana</option>
                                <option value="BOS">BOS</option>
                                <option value="Komite">Komite</option>
                                <option value="Bantuan Pemerintah">Bantuan Pemerintah</option>
                                <option value="Hibah">Hibah</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label for="deskripsi"
                                class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="3"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                placeholder="Masukkan deskripsi barang (opsional)"></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow"
                            @click="showAddModal = false">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-lg hover:from-primary/90 hover:to-secondary/90 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow shadow-sm hover:shadow-md">
                            <i class="fas fa-save mr-2"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Tambah Sub Barang -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-show="showAddSubModal" x-cloak>
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity transition-slow"
                @click="showAddSubModal = false"></div>

            <div class="relative glass rounded-xl shadow-2xl border border-gray-200/70 w-full max-w-4xl h-auto max-h-[90vh] overflow-y-auto transform transition-all"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Tambah Sub Barang Baru</h3>
                    <button type="button"
                        class="text-gray-400 hover:text-gray-500 focus:outline-none transition-slow"
                        @click="showAddSubModal = false">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.sub-barang.store') }}" class="p-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="kondisi" class="block text-sm font-medium text-gray-700 mb-1">Barang <span
                                    class="text-red-500">*</span></label>
                            {{-- <select name="barang_id" id="barang_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" required>
                                <option value="">Pilih Barang</option>
                                @foreach ($barangs as $barang)
                                    <option value="{{ $barang->id }}">{{ $barang->nama }}</option>
                                @endforeach
                            </select> --}}
                            <input type="text" name="barang_id" id="barang_id"
                                class="w-full border hidden border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                readonly required>
                            <input type="text" name="" id="barang_nama"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                readonly required>
                        </div>
                        <div>
                            <label for="kode" class="block text-sm font-medium text-gray-700 mb-1">Kode Barang
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="kode" id="kode_sub_barang"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                placeholder="Masukkan kode barang" required>
                        </div>

                        <div>
                            <label for="kondisi" class="block text-sm font-medium text-gray-700 mb-1">Kondisi <span
                                    class="text-red-500">*</span></label>
                            <select name="kondisi" id="kondisi"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                required>
                                <option value="">Pilih Kondisi</option>
                                <option value="baik">Baik</option>
                                <option value="rusak_ringan">Rusak Ringan</option>
                                <option value="rusak_berat">Rusak Berat</option>
                            </select>
                        </div>

                        <div>
                            <label for="tahun_perolehan" class="block text-sm font-medium text-gray-700 mb-1">Tahun
                                Perolehan <span class="text-red-500">*</span></label>
                            <input type="number" name="tahun_perolehan" id="tahun_perolehan"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                placeholder="Masukkan tahun perolehan" min="1900" max="{{ date('Y') }}"
                                required>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow"
                            @click="showAddSubModal = false">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-lg hover:from-primary/90 hover:to-secondary/90 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow shadow-sm hover:shadow-md">
                            <i class="fas fa-save mr-2"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit Barang -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-show="showEditModal" x-cloak>
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity transition-slow"
                @click="showEditModal = false"></div>

            <div class="relative glass rounded-xl shadow-2xl border border-gray-200/70 w-full max-w-4xl h-auto max-h-[90vh] overflow-y-auto transform transition-all"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Barang</h3>
                    <button type="button"
                        class="text-gray-400 hover:text-gray-500 focus:outline-none transition-slow"
                        @click="showEditModal = false">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form method="POST" :action="'/admin/barang/' + editItem.id" class="p-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_kode" class="block text-sm font-medium text-gray-700 mb-1">Kode Barang
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="kode" id="edit_kode"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow bg-gray-100 cursor-not-allowed"
                                x-model="editItem.kode" required readonly>
                        </div>
                        <div>
                            <label for="edit_nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Barang
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="edit_nama"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                x-model="editItem.nama" required>
                        </div>
                        <div>
                            <label for="edit_kategori_id"
                                class="block text-sm font-medium text-gray-700 mb-1">Kategori <span
                                    class="text-red-500">*</span></label>
                            <select name="kategori_id" id="edit_kategori_id"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                x-model="editItem.kategori_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="edit_satuan" class="block text-sm font-medium text-gray-700 mb-1">Satuan <span
                                    class="text-red-500">*</span></label>
                            <select name="satuan" id="edit_satuan"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
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
                            <label for="edit_ruangan_id" class="block text-sm font-medium text-gray-700 mb-1">Ruangan
                                <span class="text-red-500">*</span></label>
                            <select name="ruangan_id" id="edit_ruangan_id"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                x-model="editItem.ruangan_id" required>
                                <option value="">Pilih Ruangan</option>
                                @foreach ($ruangan as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_ruangan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="edit_sumber_dana" class="block text-sm font-medium text-gray-700 mb-1">Sumber
                                Dana <span class="text-red-500">*</span></label>
                            <select name="sumber_dana" id="edit_sumber_dana"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                x-model="editItem.sumber_dana" required>
                                <option value="">Pilih Sumber Dana</option>
                                <option value="BOS">BOS</option>
                                <option value="Komite">Komite</option>
                                <option value="Bantuan Pemerintah">Bantuan Pemerintah</option>
                                <option value="Hibah">Hibah</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label for="edit_deskripsi"
                                class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="deskripsi" id="edit_deskripsi" rows="3"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                x-model="editItem.deskripsi" placeholder="Masukkan deskripsi barang (opsional)"></textarea>
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

        <!-- Modal View Barang -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-show="showViewModal" x-cloak>
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity transition-slow"
                @click="showViewModal = false"></div>

            <div class="relative glass rounded-xl shadow-2xl border border-gray-200/70 w-full max-w-4xl h-auto max-h-[90vh] overflow-y-auto transform transition-all"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Detail Barang</h3>
                    <button type="button"
                        class="text-gray-400 hover:text-gray-500 focus:outline-none transition-slow"
                        @click="showViewModal = false">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
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
                                        <span class="text-sm font-medium text-gray-900"
                                            x-text="viewItem.kategori?.nama || '-'"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Satuan:</span>
                                        <span class="text-sm font-medium text-gray-900"
                                            x-text="viewItem.satuan"></span>
                                    </div>
                                </div>
                            </div>

                            {{-- <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-2 border-b pb-1">Kondisi</h4>
                                <div class="flex justify-between items-center">
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
                            </div> --}}
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-2 border-b pb-1">Lokasi & Perolehan
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Ruangan:</span>
                                        <span class="text-sm font-medium text-gray-900"
                                            x-text="viewItem.ruangan?.nama_ruangan || '-'"></span>
                                    </div>

                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Sumber Dana:</span>
                                        <span class="text-sm font-medium text-gray-900"
                                            x-text="viewItem.sumber_dana || '-'"></span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-2 border-b pb-1">Informasi Sistem</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Dibuat Pada:</span>
                                        <span class="text-sm font-medium text-gray-900"
                                            x-text="new Date(viewItem.created_at).toLocaleString('id-ID')"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Terakhir Diupdate:</span>
                                        <span class="text-sm font-medium text-gray-900"
                                            x-text="new Date(viewItem.updated_at).toLocaleString('id-ID')"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi (Full Width) -->
                        <div class="md:col-span-2">
                            <h4 class="text-sm font-medium text-gray-500 mb-2 border-b pb-1">Deskripsi</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-700" x-text="viewItem.deskripsi || 'Tidak ada deskripsi'">
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Sub Barang Section -->
                    <div class="mt-6">
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center space-x-3">
                                <h4 class="text-lg font-semibold text-gray-800">Daftar Sub Barang</h4>
                                <div class="text-xs text-gray-500" x-show="subBarangData[viewItem.id]">
                                    Terakhir update: <span x-text="new Date().toLocaleTimeString('id-ID')"></span>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button
                                    class="bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-slow shadow-sm hover:shadow-md text-sm"
                                    @click="openAddSubModal()">
                                    <i class="fas fa-plus"></i>
                                    <span>Tambah Sub Barang</span>
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto rounded-lg border border-gray-200/70 shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200/70">
                                <thead class="bg-gray-50/80">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            No</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Kode Sub Barang</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Kondisi</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Status</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Tahun Perolehan</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Bisa Dipinjam</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200/70">
                    <template x-if="subBarangData[viewItem.id] && subBarangData[viewItem.id].length > 0">
                        <template x-for="(sub, index) in subBarangData[viewItem.id]" :key="sub.id">
                            <tr class="table-row-hover transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    x-text="index + 1"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                    x-text="sub.kode"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="badge"
                                        :class="{
                                            'badge-success': sub.kondisi === 'baik',
                                            'badge-warning': sub.kondisi === 'rusak_ringan',
                                            'badge-danger': sub.kondisi === 'rusak_berat',
                                            'badge-gray': sub.kondisi === 'nonaktif'
                                        }"
                                        x-text="sub.kondisi === 'baik' ? 'Baik' : 
                                                sub.kondisi === 'rusak_ringan' ? 'Rusak Ringan' : 
                                                sub.kondisi === 'rusak_berat' ? 'Rusak Berat' :
                                                sub.kondisi === 'nonaktif' ? 'Nonaktif' : 'Tidak Diketahui'">
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <template x-if="sub.status_peminjaman === 'dipinjam' || sub.kondisi === 'nonaktif'">
                                        <div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-user-clock mr-1"></i>
                                                Tidak Tersedia
                                            </span>
                                            <div class="text-xs text-gray-500 mt-1" x-text="sub.kondisi === 'nonaktif' ? 'Kondisi nonaktif' : (sub.peminjam_info || 'Sedang dipinjam')"></div>
                                        </div>
                                    </template>
                                    <template x-if="(sub.status_peminjaman === 'tersedia' || !sub.status_peminjaman) && sub.kondisi !== 'nonaktif'">
                                        <div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Tersedia
                                            </span>
                                        </div>
                                    </template>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    x-text="sub.tahun_perolehan"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <template x-if="sub.kondisi === 'rusak_ringan'">
                                        <span class="inline-flex items-center">
                                            <span :class="sub.bisa_dipinjam ? 'text-green-600' : 'text-red-600'">
                                                <i :class="sub.bisa_dipinjam ? 'fas fa-check-circle' : 'fas fa-times-circle'"></i>
                                                <span class="ml-1" x-text="sub.bisa_dipinjam ? 'Ya' : 'Tidak'"></span>
                                            </span>
                                        </span>
                                    </template>
                                    <template x-if="sub.kondisi === 'baik'">
                                        <span class="inline-flex items-center text-green-600">
                                            <i class="fas fa-check-circle"></i>
                                            <span class="ml-1">Ya</span>
                                        </span>
                                    </template>
                                    <template x-if="sub.kondisi === 'rusak_berat' || sub.kondisi === 'nonaktif'">
                                        <span class="inline-flex items-center text-red-600">
                                            <i class="fas fa-times-circle"></i>
                                            <span class="ml-1">Tidak</span>
                                        </span>
                                    </template>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button
                                            class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-slow"
                                            @click="openDetailSubModal(sub)" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button
                                            class="text-indigo-600 hover:text-indigo-900 p-2 rounded-lg hover:bg-indigo-50 transition-slow"
                                            @click="openEditSubModal(sub)" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        </template>
                    </template>
                    <template x-if="!subBarangData[viewItem.id] || subBarangData[viewItem.id].length === 0">
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                <template x-if="!subBarangData[viewItem.id]">
                                    <div>
                                        <i class="fas fa-spinner fa-spin mr-2"></i>
                                        Memuat data sub barang...
                                    </div>
                                </template>
                                <template x-if="subBarangData[viewItem.id] && subBarangData[viewItem.id].length === 0">
                                    <div>Tidak ada sub barang yang tersedia</div>
                                </template>
                            </td>
                        </tr>
                    </template>
                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200/70 flex justify-end space-x-3">
                    {{-- <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow" @click="showViewModal = false">
                        Tutup
                    </button> --}}
                </div>
            </div>
        </div>



        <!-- Modal Edit Sub Barang -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-show="showEditSubModal" x-cloak>
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity transition-slow"
                @click="showEditSubModal = false"></div>

            <div class="relative glass rounded-xl shadow-2xl border border-gray-200/70 w-full max-w-2xl h-auto max-h-[90vh] overflow-y-auto transform transition-all"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Sub Barang</h3>
                    <button type="button"
                        class="text-gray-400 hover:text-gray-500 focus:outline-none transition-slow"
                        @click="showEditSubModal = false">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form @submit.prevent="updateSubBarang" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Barang</label>
                            <input type="text" 
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow bg-gray-50"
                                x-model="editSubItem.barang_nama" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Sub Barang <span class="text-red-500">*</span></label>
                            <input type="text" 
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow bg-gray-100 cursor-not-allowed"
                                x-model="editSubItem.kode" required readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi <span class="text-red-500">*</span></label>
                            <select class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                x-model="editSubItem.kondisi" required>
                                <option value="">Pilih Kondisi</option>
                                <option value="baik">Baik</option>
                                <option value="rusak_ringan">Rusak Ringan</option>
                                <option value="rusak_berat">Rusak Berat</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Perolehan <span class="text-red-500">*</span></label>
                            <input type="number" 
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                x-model="editSubItem.tahun_perolehan" min="1900" :max="new Date().getFullYear()" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <textarea 
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow"
                                x-model="editSubItem.catatan" rows="3" placeholder="Catatan tentang sub barang ini..."></textarea>
                        </div>
                        <div class="md:col-span-2" x-show="editSubItem.kondisi === 'rusak_ringan'">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" 
                                    class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 focus:ring-2"
                                    x-model="editSubItem.bisa_dipinjam">
                                <span class="text-sm font-medium text-gray-700">Bisa dipinjam</span>
                                <span class="text-xs text-gray-500">(Hanya bisa diubah untuk kondisi rusak ringan)</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow"
                            @click="showEditSubModal = false">
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

        <!-- Modal Detail Sub Barang -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-show="showDetailSubModal" x-cloak>
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity transition-slow"
                @click="showDetailSubModal = false"></div>

            <div class="relative glass rounded-xl shadow-2xl border border-gray-200/70 w-full max-w-2xl h-auto max-h-[90vh] overflow-y-auto transform transition-all"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Detail Sub Barang</h3>
                    <button type="button"
                        class="text-gray-400 hover:text-gray-500 focus:outline-none transition-slow"
                        @click="showDetailSubModal = false">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Barang</label>
                            <div class="px-4 py-2 bg-gray-50 rounded-lg text-gray-800" x-text="detailSubItem.barang_nama"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Sub Barang</label>
                            <div class="px-4 py-2 bg-gray-50 rounded-lg text-gray-800" x-text="detailSubItem.kode"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi</label>
                            <div class="px-4 py-2 bg-gray-50 rounded-lg">
                                <span class="badge" :class="{
                                    'badge-success': detailSubItem.kondisi === 'baik',
                                    'badge-warning': detailSubItem.kondisi === 'rusak_ringan',
                                    'badge-danger': detailSubItem.kondisi === 'rusak_berat',
                                    'badge-gray': detailSubItem.kondisi === 'nonaktif'
                                }" x-text="detailSubItem.kondisi === 'baik' ? 'Baik' : 
                                          detailSubItem.kondisi === 'rusak_ringan' ? 'Rusak Ringan' : 
                                          detailSubItem.kondisi === 'rusak_berat' ? 'Rusak Berat' :
                                          detailSubItem.kondisi === 'nonaktif' ? 'Nonaktif' : 'Tidak Diketahui'"></span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Perolehan</label>
                            <div class="px-4 py-2 bg-gray-50 rounded-lg text-gray-800" x-text="detailSubItem.tahun_perolehan"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bisa Dipinjam</label>
                            <div class="px-4 py-2 bg-gray-50 rounded-lg">
                                <template x-if="detailSubItem.kondisi === 'rusak_ringan'">
                                    <span class="inline-flex items-center">
                                        <span :class="detailSubItem.bisa_dipinjam ? 'text-green-600' : 'text-red-600'">
                                            <i :class="detailSubItem.bisa_dipinjam ? 'fas fa-check-circle' : 'fas fa-times-circle'"></i>
                                            <span class="ml-1" x-text="detailSubItem.bisa_dipinjam ? 'Ya' : 'Tidak'"></span>
                                        </span>
                                    </span>
                                </template>
                                <template x-if="detailSubItem.kondisi !== 'rusak_ringan'">
                                    <span class="text-gray-500">
                                        <i :class="(detailSubItem.kondisi === 'baik') ? 'fas fa-check-circle text-green-600' : 'fas fa-times-circle text-red-600'"></i>
                                        <span class="ml-1" x-text="(detailSubItem.kondisi === 'baik') ? 'Ya (Otomatis)' : 'Tidak (Otomatis)'"></span>
                                    </span>
                                </template>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <div class="px-4 py-2 bg-gray-50 rounded-lg text-gray-800 min-h-[80px]" x-text="detailSubItem.catatan || 'Tidak ada catatan'"></div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="button"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow"
                            @click="showDetailSubModal = false">
                            Tutup
                        </button>
                    </div>
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
                showEditSubModal: false,
                showDetailSubModal: false,
                showViewModal: false,

                subBarangCache: {},
                subBarangData: {},
                editItem: {
                    id: '',
                    kode: '',
                    nama: '',
                    kategori_id: '',
                    satuan: '',
                    ruangan_id: '',
                    sumber_dana: '',
                    deskripsi: ''
                },
                editSubItem: {
                    id: '',
                    barang_id: '',
                    barang_nama: '',
                    kode: '',
                    kondisi: '',
                    tahun_perolehan: '',
                    catatan: '',
                    bisa_dipinjam: true
                },
                detailSubItem: {
                    id: '',
                    barang_id: '',
                    barang_nama: '',
                    kode: '',
                    kondisi: '',
                    tahun_perolehan: '',
                    catatan: '',
                    bisa_dipinjam: true
                },
                viewItem: {},

                // Method to generate barang code when modal is opened
                async generateBarangCodeOnOpen() {
                    try {
                        const response = await fetch('/admin/barang/count', {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        
                        const data = await response.json();
                        const barangCount = data.count;
                        const nextNumber = barangCount + 1;
                        const paddedNumber = nextNumber.toString().padStart(3, '0');
                        
                        // Set the code in the input field
                        const kodeInput = document.getElementById('kode');
                        if (kodeInput) {
                            kodeInput.value = 'BRG-' + paddedNumber;
                        }
                    } catch (error) {
                        console.error('Error fetching barang count:', error);
                        const kodeInput = document.getElementById('kode');
                        if (kodeInput) {
                            kodeInput.value = 'BRG-001';
                        }
                    }
                },

                // Watch for modal opening to generate code
                init() {
                    this.$watch('showAddModal', (value) => {
                        if (value) {
                            // Modal is opened, generate code after a short delay
                            setTimeout(() => {
                                this.generateBarangCodeOnOpen();
                            }, 100);
                        }
                    });
                },

                openEditModal(item) {
                    this.editItem = {
                        id: item.id,
                        kode: item.kode,
                        nama: item.nama,
                        kategori_id: item.kategori_id,
                        satuan: item.satuan,
                        ruangan_id: item.ruangan_id,
                        sumber_dana: item.sumber_dana,
                        deskripsi: item.deskripsi
                    };
                    this.showEditModal = true;
                },

                async openViewModal(item) {
                    this.viewItem = {
                        ...item
                    };
                    this.showViewModal = true;
                    
                    // Load sub barang data with current status
                    try {
                        const subBarangs = await this.getSubBarangs(item.id);
                        this.subBarangData[item.id] = subBarangs;
                    } catch (error) {
                        console.error('Error loading sub barangs:', error);
                        // Fallback to static data
                        const allSubBarangs = {!! $subBarangs !!};
                        this.subBarangData[item.id] = allSubBarangs.filter(sub => sub.barang_id == item.id);
                    }
                },
                
                async getSubBarangs(barangId) {
                    // Use cached data if available and not expired
                    const cacheKey = `subBarangs_${barangId}`;
                    const cached = this.subBarangCache[cacheKey];
                    const now = Date.now();
                    
                    if (cached && (now - cached.timestamp < 30000)) { // 30 seconds cache
                        return cached.data;
                    }
                    
                    try {
                        const response = await fetch(`/admin/sub-barang/by-barang/${barangId}`);
                        const data = await response.json();
                        
                        // Cache the result
                        this.subBarangCache[cacheKey] = {
                            data: data,
                            timestamp: now
                        };
                        
                        return data;
                    } catch (error) {
                        console.error('Error fetching sub barangs:', error);
                        // Fallback to static data
                        const allSubBarangs = {!! $subBarangs !!};
                        return allSubBarangs.filter(sub => sub.barang_id == barangId);
                    }
                },

                openEditSubModal(sub) {
                    this.editSubItem = {
                        id: sub.id,
                        barang_id: sub.barang_id,
                        barang_nama: this.viewItem.nama,
                        kode: sub.kode,
                        kondisi: sub.kondisi,
                        tahun_perolehan: sub.tahun_perolehan,
                        catatan: sub.catatan || '',
                        bisa_dipinjam: sub.bisa_dipinjam !== undefined ? sub.bisa_dipinjam : true
                    };
                    this.showEditSubModal = true;
                },

                openDetailSubModal(sub) {
                    this.detailSubItem = {
                        id: sub.id,
                        barang_id: sub.barang_id,
                        barang_nama: this.viewItem.nama,
                        kode: sub.kode,
                        kondisi: sub.kondisi,
                        tahun_perolehan: sub.tahun_perolehan,
                        catatan: sub.catatan || '',
                        bisa_dipinjam: sub.bisa_dipinjam !== undefined ? sub.bisa_dipinjam : true
                    };
                    this.showDetailSubModal = true;
                },

                updateSubBarang() {
                    // Get CSRF token safely
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                    if (!csrfToken) {
                        console.error('CSRF token not found');
                        alert('Terjadi kesalahan: CSRF token tidak ditemukan');
                        return;
                    }
                    
                    const formData = new FormData();
                    formData.append('_method', 'PUT');
                    formData.append('_token', csrfToken);
                    formData.append('barang_id', this.editSubItem.barang_id);
                    formData.append('kode', this.editSubItem.kode);
                    formData.append('kondisi', this.editSubItem.kondisi);
                    formData.append('tahun_perolehan', this.editSubItem.tahun_perolehan);
                    formData.append('catatan', this.editSubItem.catatan || '');
                    formData.append('bisa_dipinjam', this.editSubItem.bisa_dipinjam ? '1' : '0');

                    console.log('Updating sub barang:', this.editSubItem);
                    // Convert FormData to object for logging
                    const formDataObj = {};
                    for (let [key, value] of formData.entries()) {
                        formDataObj[key] = value;
                    }
                    console.log('Form data:', formDataObj);

                    console.log('Sending request to:', `/admin/sub-barang/${this.editSubItem.id}`);
                    
                    fetch(`/admin/sub-barang/${this.editSubItem.id}`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        console.log('Response headers:', response.headers);
                        
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        if (data.success) {
                            this.showEditSubModal = false;
                            
                            // Show success message with updated status
                            const newStatus = this.editSubItem.kondisi;
                            const statusText = newStatus === 'baik' ? 'Baik' : 
                                             newStatus === 'rusak_ringan' ? 'Rusak Ringan' : 
                                             newStatus === 'rusak_berat' ? 'Rusak Berat' :
                                             newStatus === 'nonaktif' ? 'Nonaktif' : 'Tidak Diketahui';
                            
                            alert(`Sub barang berhasil diperbarui!\nStatus: ${statusText}`);
                            
                            // Reload sub barang data to show updated status
                            this.loadSubBarangData(this.editSubItem.barang_id);
                        } else {
                            alert('Terjadi kesalahan: ' + (data.message || 'Gagal memperbarui sub barang'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan: ' + error.message);
                        this.loadSubBarangData(this.editSubItem.barang_id);
                    });
                },

                async openAddSubModal() {
                    this.showViewModal = false;
                    this.showAddSubModal = true;
                    
                    // Load current sub barang data for code generation
                    const existingSubBarangs = this.subBarangData[this.viewItem.id] || [];
                    
                    this.$nextTick(() => {
                        document.getElementById('barang_id').value = this.viewItem.id;
                        document.getElementById('barang_nama').value = this.viewItem.nama;
                        
                        // Generate sub barang code by finding the first available increment
                        const nextIncrement = this.findNextAvailableIncrement(existingSubBarangs, this.viewItem.kode);
                        const paddedIncrement = nextIncrement.toString().padStart(3, '0');
                        const newSubCode = this.viewItem.kode + '-' + paddedIncrement;
                        
                        document.getElementById('kode_sub_barang').value = newSubCode;
                    });
                },

                findNextAvailableIncrement(existingSubBarangs, parentCode) {
                    // Extract increment numbers from existing sub barang codes
                    const existingIncrements = existingSubBarangs
                        .map(sub => {
                            // Extract the increment part after the last dash
                            const parts = sub.kode.split('-');
                            const incrementPart = parts[parts.length - 1];
                            return parseInt(incrementPart, 10);
                        })
                        .filter(num => !isNaN(num)) // Filter out invalid numbers
                        .sort((a, b) => a - b); // Sort numerically

                    // Find the first missing number in the sequence
                    let nextIncrement = 1;
                    for (let i = 0; i < existingIncrements.length; i++) {
                        if (existingIncrements[i] === nextIncrement) {
                            nextIncrement++;
                        } else if (existingIncrements[i] > nextIncrement) {
                            // Found a gap, use the current nextIncrement
                            break;
                        }
                    }

                    return nextIncrement;
                },

                // Method to load sub barang data
                async loadSubBarangData(barangId) {
                    // Clear cache for this barang
                    const cacheKey = `subBarangs_${barangId}`;
                    delete this.subBarangCache[cacheKey];
                    
                    // Reload data
                    try {
                        const subBarangs = await this.getSubBarangs(barangId);
                        this.subBarangData[barangId] = subBarangs;
                        
                        // Log the updated data for debugging
                        console.log('Sub barang data reloaded:', subBarangs);
                    } catch (error) {
                        console.error('Error reloading sub barangs:', error);
                    }
                },




            }
        }

        // Toggle sidebar for mobile
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
        });

        // Toggle sidebar for mobile
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
        });
    </script>
</body>

</html>
