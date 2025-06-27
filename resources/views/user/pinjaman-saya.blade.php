<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjaman Saya - SchoolLend</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#8B5CF6',
                    }
                }
            }
        }
    </script>
    <style>
        .dark-theme {
            --bg-primary: #1f2937;
            --bg-secondary: #374151;
            --text-primary: #f9fafb;
            --text-secondary: #d1d5db;
        }
        
        .dark-theme body {
            background: linear-gradient(to bottom right, var(--bg-primary), var(--bg-secondary));
            color: var(--text-primary);
        }
        
        .dark-theme .bg-white\/70 {
            background: rgba(55, 65, 81, 0.7);
            border-color: rgba(75, 85, 99, 0.3);
        }
        
        .dark-theme .text-gray-900 {
            color: var(--text-primary);
        }
        
        .dark-theme .text-gray-600 {
            color: var(--text-secondary);
        }
        
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
        
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }
        
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        @media (max-width: 768px) {
            .grid-cols-2 {
                grid-template-columns: repeat(1, minmax(0, 1fr));
            }
            
            .flex-col.sm\:flex-row {
                flex-direction: column;
            }
            
            .gap-6 {
                gap: 1rem;
            }
            
            .p-8 {
                padding: 1.5rem;
            }
            
            .text-4xl {
                font-size: 2rem;
            }
            
            .text-2xl {
                font-size: 1.5rem;
            }
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                background: white !important;
                color: black !important;
            }
            
            .bg-gradient-to-br,
            .bg-gradient-to-r {
                background: white !important;
            }
            
            .shadow-lg,
            .shadow-xl {
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <div class="min-h-screen flex flex-col">
        <header class="bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-sm sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex items-center justify-between py-4">
                    <a href="#" class="flex items-center gap-3 text-primary font-bold text-2xl">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-graduation-cap text-lg"></i>
                        </div>
                        <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">SchoolLend</span>
                    </a>
                    
                    <nav class="flex items-center gap-2">
                        <a href="/user/dashboard-user" class="px-4 py-2 text-gray-600 hover:text-primary hover:bg-primary/10 rounded-xl transition-all">
                            <i class="fas fa-home mr-2"></i>Beranda
                        </a>
                        <a href="/user/pinjaman-saya" class="px-4 py-2 text-white bg-gradient-to-r from-primary to-secondary font-semibold rounded-xl shadow-lg">
                            <i class="fas fa-book-open mr-2"></i>Pinjaman 
                        </a>
                        <a href="/user/riwayat" class="px-4 py-2 text-gray-600 hover:text-primary hover:bg-primary/10 rounded-xl transition-all">
                            <i class="fas fa-history mr-2"></i>Riwayat
                        </a>
                        <a href="/user/profile" class="px-4 py-2 text-gray-600 hover:text-primary hover:bg-primary/10 rounded-xl transition-all">
                            <i class="fas fa-user mr-2"></i>Profile
                        </a>
                    </nav>
                    
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white font-semibold shadow-lg">
                            {{ substr(Auth::user()->name, 0, 1) }}{{ substr(strstr(Auth::user()->name, ' '), 1, 1) }}
                        </div>
                        <div class="hidden md:block">
                            <h3 class="font-semibold text-gray-900">{{ Auth::user()->name }}</h3>
                            <p class="text-sm text-gray-600">SMAN 1  Cikalong Wetan</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-grow">
            <div class="max-w-7xl mx-auto px-6 py-8">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Pinjaman Saya</h1>
                    <p class="text-gray-600">Pantau semua barang yang sedang Anda pinjam dari perpustakaan sekolah.</p>
                </div>

                <div class="bg-white/70 backdrop-blur-sm rounded-3xl shadow-lg border border-gray-100 p-8 mb-8">
                    <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 flex-1">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-filter text-primary"></i>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-700 text-sm">Filter Status:</span>
                                    <select class="block mt-1 px-4 py-2 border border-gray-200 rounded-xl text-sm bg-white min-w-40 focus:ring-2 focus:ring-primary/20 focus:border-primary" id="statusFilter">
                                        <option value="all">Semua Status</option>
                                        <option value="active">Aktif</option>
                                        <option value="due-soon">Segera Berakhir</option>
                                        <option value="overdue">Terlambat</option>
                                    </select>
                                </div>
                            </div>
                            
                            {{-- <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-secondary/10 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-tags text-secondary"></i>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-700 text-sm">Filter Kategori:</span>
                                    <select class="block mt-1 px-4 py-2 border border-gray-200 rounded-xl text-sm bg-white min-w-40 focus:ring-2 focus:ring-primary/20 focus:border-primary" id="categoryFilter">
                                        <option value="all">Semua Kategori</option>
                                        <option value="buku">Buku</option>
                                        <option value="alat-tulis">Alat Tulis</option>
                                        <option value="elektronik">Elektronik</option>
                                        <option value="olahraga">Olahraga</option>
                                    </select>
                                </div>
                            </div> --}}
                        </div>
                        
                        <button class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all text-sm font-medium">
                            <i class="fas fa-times mr-2"></i>Reset Filter
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="borrowingsList">
                    @forelse($peminjamanData as $item)
                        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                            <div class="flex flex-col h-full">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center">
                                            <i class="text-sm @php
                                                echo match($item['category']) {
                                                    'buku' => 'fas fa-book text-blue-500',
                                                    'alat-tulis' => 'fas fa-pencil-alt text-amber-500',
                                                    'elektronik' => 'fas fa-laptop text-purple-500',
                                                    'olahraga' => 'fas fa-futbol text-emerald-500',
                                                    default => 'fas fa-box text-gray-500'
                                                };
                                            @endphp"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-medium text-gray-900">{{ $item['itemName'] }}</h3>
                                            <span class="text-xs text-gray-400">{{ $item['categoryName'] }}</span>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium 
                                        @php
                                            echo match(true) {
                                                $item['status'] == 'pending' => 'bg-amber-50 text-amber-700',
                                                $item['isOverdue'] || $item['status'] == 'terlambat' => 'bg-red-50 text-red-700',
                                                $item['status'] == 'rusak' => 'bg-gray-100 text-gray-700',
                                                default => 'bg-green-50 text-green-700'
                                            };
                                        @endphp">
                                        {{ $item['statusDisplay']['text'] }}
                                    </span>
                                </div>
                                
                                <div class="flex items-center justify-between mb-5 px-1">
                                    <div class="text-center">
                                        <p class="text-xs text-gray-400 mb-1">Pinjam</p>
                                        <p class="text-sm font-medium text-gray-700">
                                            {{ \Carbon\Carbon::parse($item['borrowDate'])->format('d M Y') }}
                                        </p>
                                    </div>
                                    <div class="h-px bg-gray-200 flex-1 mx-3"></div>
                                    <div class="text-center">
                                        <p class="text-xs text-gray-400 mb-1">Kembali</p>
                                        <p class="text-sm font-medium text-gray-700">
                                            {{ \Carbon\Carbon::parse($item['returnDate'])->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="mb-4 px-3 py-2 rounded-lg bg-gray-50/50">
                                    <div class="flex justify-end text-xs">
                                        <span class="text-gray-500 font-medium">Jumlah: {{ $item['quantity'] }}</span>
                                    </div>
                                </div>
                                
                                <div class="mt-auto bg-gray-50/50 rounded-xl p-4">
                                    <p class="text-xs font-medium text-gray-500 mb-1">Tujuan Pinjam</p>
                                    <p class="text-sm text-gray-700">{{ $item['purpose'] }}</p>
                                    
                                    @if($item['notes'])
                                        <div class="mt-2 pt-2 border-t border-gray-200/50">
                                            <p class="text-xs text-gray-500">{{ $item['notes'] }}</p>
                                        </div>
                                    @endif
                                </div>
                                
                                @if($item['isOverdue'] || $item['status'] == 'terlambat')
                                    <div class="mt-3 bg-red-50/70 rounded-xl p-3 flex items-start gap-2">
                                        <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 text-sm"></i>
                                        <p class="text-xs text-red-600">Segera kembalikan barang untuk menghindari sanksi</p>
                                    </div>
                                @elseif($item['isDueSoon'])
                                    <div class="mt-3 bg-amber-50/70 rounded-xl p-3 flex items-start gap-2">
                                        <i class="fas fa-clock text-amber-500 mt-0.5 text-sm"></i>
                                        <p class="text-xs text-amber-600">Masa pinjam akan segera berakhir</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 flex flex-col items-center">
                            <div class="w-20 h-20 rounded-full bg-gray-50 flex items-center justify-center mb-4">
                                <i class="fas fa-book-open text-gray-300 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-700 mb-2">Tidak ada peminjaman aktif</h3>
                            <p class="text-gray-400 text-sm mb-5">Anda belum memiliki barang yang sedang dipinjam</p>
                            <a href="{{ route('user.dashboard') }}" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-800 transition-colors">
                                Pinjam Barang Sekarang
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>

        <footer class="bg-gray-50/90 backdrop-blur-sm border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto px-6 py-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-school text-primary"></i>
                            SMAN 1 Cikalongwetan
                        </h3>
                        <div class="space-y-2 text-sm text-gray-700">
                            <p class="flex items-center gap-2">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                                Jl. Cikalong No.153, Mandalasari, Kec. Cikalong Wetan, Kabupaten Bandung Barat, Jawa Barat 40556
                            </p>
                            <p class="flex items-center gap-2">
                                <i class="fas fa-calendar-alt text-primary"></i>
                                Didirikan: 9 November 1983
                            </p>
                            <p class="flex items-center gap-2">
                                <i class="fas fa-star text-primary"></i>
                                Akreditasi: A
                            </p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-link text-primary"></i>
                            Tautan Cepat
                        </h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="#" class="text-gray-700 hover:text-primary transition flex items-center gap-2">
                                    <i class="fas fa-chevron-right text-xs text-primary"></i>
                                    Beranda
                                </a>
                            </li>
                            <li>
                                <a href="/user/pinjaman-saya" class="text-gray-700 hover:text-primary transition flex items-center gap-2">
                                    <i class="fas fa-chevron-right text-xs text-primary"></i>
                                    Pinjaman Saya
                                </a>
                            </li>
                            <li>
                                <a href="/user/riwayat" class="text-gray-700 hover:text-primary transition flex items-center gap-2">
                                    <i class="fas fa-chevron-right text-xs text-primary"></i>
                                    Riwayat Peminjaman
                                </a>
                            </li>
                            <li>
                                <a href="/user/profile" class="text-gray-700 hover:text-primary transition flex items-center gap-2">
                                    <i class="fas fa-chevron-right text-xs text-primary"></i>
                                    Profil Pengguna
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-code text-primary"></i>
                            Pengembang
                        </h3>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                                NM
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Nita Misbahullail</h4>
                                <p class="text-sm text-gray-700">Pengembang Sistem SchoolLend</p>
                            </div>
                        </div>
                        <p class="text-gray-700 text-sm">
                            Sistem SchoolLend dikembangkan untuk memenuhi kebutuhan manajemen peminjaman barang di SMAN 1 Cikalongwetan.
                        </p>
                        <div class="flex gap-4">
                            <a href="#" class="text-gray-700 hover:text-primary transition text-xl">
                                <i class="fab fa-github"></i>
                            </a>
                            <a href="#" class="text-gray-700 hover:text-primary transition text-xl">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="text-gray-700 hover:text-primary transition text-xl">
                                <i class="fab fa-linkedin"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-300 mt-8 pt-6 text-center text-gray-600 text-sm">
                    <p>&copy; 2024 SchoolLend. Sistem Peminjaman Barang Sekolah. Dikembangkan untuk SMAN 1 Cikalongwetan.</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
    // Fungsi untuk memfilter pinjaman berdasarkan status
    function filterBorrowings() {
        const statusFilter = document.getElementById('statusFilter').value;
        let hasResults = false;
        
        document.querySelectorAll('#borrowingsList > div').forEach(card => {
            // Skip pesan "tidak ada peminjaman" jika ada
            if (card.classList.contains('no-results-message')) {
                card.style.display = 'none';
                return;
            }
            
            const statusElement = card.querySelector('span');
            const status = statusElement.textContent.toLowerCase();
            
            // Logika pencocokan status
            let statusMatch = false;
            
            switch(statusFilter) {
                case 'all':
                    statusMatch = true;
                    break;
                case 'active':
                    statusMatch = !status.includes('terlambat') && 
                                 !status.includes('pending') && 
                                 !status.includes('segera');
                    break;
                case 'due-soon':
                    statusMatch = status.includes('segera');
                    break;
                case 'overdue':
                    statusMatch = status.includes('terlambat');
                    break;
            }
            
            // Tampilkan/sembunyikan card berdasarkan status
            card.style.display = statusMatch ? 'block' : 'none';
            
            if (statusMatch) hasResults = true;
        });
        
        // Tampilkan pesan jika tidak ada hasil
        const noResultsElement = document.getElementById('noResultsMessage');
        if (!hasResults) {
            if (!noResultsElement) {
                const noResultsHTML = `
                    <div id="noResultsMessage" class="col-span-full py-12 flex flex-col items-center no-results-message">
                        <div class="w-20 h-20 rounded-full bg-gray-50 flex items-center justify-center mb-4">
                            <i class="fas fa-search text-gray-300 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-700 mb-2">Tidak ditemukan pinjaman</h3>
                        <p class="text-gray-400 text-sm mb-5">Tidak ada pinjaman yang cocok dengan filter ini</p>
                        <button onclick="clearFilters()" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-800 transition-colors">
                            Reset Filter
                        </button>
                    </div>
                `;
                document.getElementById('borrowingsList').insertAdjacentHTML('beforeend', noResultsHTML);
            } else {
                noResultsElement.style.display = 'flex';
            }
        } else if (noResultsElement) {
            noResultsElement.style.display = 'none';
        }
    }

    // Fungsi untuk mereset filter
    function clearFilters() {
        document.getElementById('statusFilter').value = 'all';
        filterBorrowings();
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi filter saat halaman dimuat
        filterBorrowings();
        
        // Event listener untuk perubahan filter status
        document.getElementById('statusFilter').addEventListener('change', filterBorrowings);
        
        // Event listener untuk tombol reset
        document.querySelector('button').addEventListener('click', clearFilters);
    });
</script>
</body>
</html>