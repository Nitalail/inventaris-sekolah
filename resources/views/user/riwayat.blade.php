<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman - SchoolLend</title>
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
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .history-item {
            transition: all 0.3s ease;
        }
        
        .history-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-center justify-between py-4">
                <!-- Logo -->
                <a href="#" class="flex items-center gap-3 text-primary font-bold text-2xl">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-graduation-cap text-lg"></i>
                    </div>
                    <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">SchoolLend</span>
                </a>
                
                <!-- Navigation -->
                <nav class="flex items-center gap-2">
                    <a href="/user/dashboard-user" class="px-4 py-2 text-gray-600 hover:text-primary hover:bg-primary/10 rounded-xl transition-all">
                        <i class="fas fa-home mr-2"></i>Beranda
                    </a>
                    <a href="/user/pinjaman-saya" class="px-4 py-2 text-gray-600 hover:text-primary hover:bg-primary/10 rounded-xl transition-all">
                        <i class="fas fa-book-open mr-2"></i>Pinjaman
                    </a>
                    <a href="/user/riwayat" class="px-4 py-2 text-white bg-gradient-to-r from-primary to-secondary font-semibold rounded-xl shadow-lg">
                        <i class="fas fa-history mr-2"></i>Riwayat
                    </a>
                    <a href="/user/profile" class="px-4 py-2 text-gray-600 hover:text-primary hover:bg-primary/10 rounded-xl transition-all">
                        <i class="fas fa-user mr-2"></i>Profile
                    </a>
                </nav>
                
                <!-- User Profile -->
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white font-semibold shadow-lg">
                        {{ substr(Auth::user()->name, 0, 1) }}{{ substr(strstr(Auth::user()->name, ' '), 1, 1) }}
                    </div>
                    <div class="hidden md:block">
                        <h3 class="font-semibold text-gray-900">{{ Auth::user()->name }}</h3>
                        <p class="text-sm text-gray-600">SMAN 1 Cikalong Wetan</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Riwayat Peminjaman</h1>
            <p class="text-gray-600">Riwayat lengkap barang yang telah Anda pinjam dan kembalikan.</p>
        </div>

        <!-- Search Box -->
        <div class="bg-white/70 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 p-6 mb-8">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" id="searchInput" class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="Cari riwayat peminjaman..." oninput="applySearch()">
            </div>
        </div>

        <!-- History List -->
        <div class="space-y-6" id="historyList">
            @foreach($riwayatData as $item)
            <div class="history-item bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6 transition-all duration-300" 
                 data-search="{{ strtolower($item['itemName']) }}">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 {{ $item['isOnTime'] ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas {{ $item['isOnTime'] ? 'fa-check' : 'fa-exclamation' }} text-lg"></i>
                    </div>
                    
                    <div class="flex-1">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $item['itemName'] }}</h3>
                                <span class="inline-block px-3 py-1 bg-purple-100 text-purple-700 text-sm font-medium rounded-full">
                                    {{ $item['categoryName'] }}
                                </span>
                            </div>
                            <span class="px-3 py-1 {{ $item['isOnTime'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} text-sm font-semibold rounded-full">
                                {{ $item['isOnTime'] ? 'Tepat Waktu' : 'Terlambat ' . $item['lateDays'] . ' hari' }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-calendar text-gray-400"></i>
                                <div>
                                    <p class="text-sm text-gray-600">Tgl Pinjam</p>
                                    <p class="font-semibold text-gray-900">{{ date('d M Y', strtotime($item['borrowDate'])) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-calendar-check text-gray-400"></i>
                                <div>
                                    <p class="text-sm text-gray-600">Tgl Kembali</p>
                                    <p class="font-semibold text-gray-900">{{ date('d M Y', strtotime($item['actualReturnDate'])) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-clock text-gray-400"></i>
                                <div>
                                    <p class="text-sm text-gray-600">Durasi</p>
                                    <p class="font-semibold text-gray-900">{{ $item['duration'] }} hari</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-cube text-gray-400"></i>
                                <div>
                                    <p class="text-sm text-gray-600">Jumlah</p>
                                    <p class="font-semibold text-gray-900">{{ $item['quantity'] }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-blue-50 border-l-4 border-primary p-4 rounded-lg">
                            <p class="text-sm text-gray-700">{{ $item['purpose'] }}</p>
                            @if($item['notes'])
                                <p class="text-xs text-gray-600 mt-2">Catatan: {{ $item['notes'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Empty state message (hidden by default) -->
            <div id="noResultsMessage" class="hidden col-span-full py-12">
                <div class="flex flex-col items-center">
                    <div class="w-20 h-20 rounded-full bg-gray-50 flex items-center justify-center mb-4">
                        <i class="fas fa-search text-gray-300 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">Tidak ditemukan hasil</h3>
                    <p class="text-gray-400 text-sm mb-5">Tidak ada riwayat peminjaman yang cocok dengan pencarian Anda</p>
                    <button onclick="clearSearch()" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-gray-800 transition-colors">
                        Reset Pencarian
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-50/90 backdrop-blur-sm border-t border-gray-200 mt-16">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- School Information -->
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

                <!-- Quick Links -->
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
                            <a href="{{ route('user.pinjaman-saya') }}" class="text-gray-700 hover:text-primary transition flex items-center gap-2">
                                <i class="fas fa-chevron-right text-xs text-primary"></i>
                                Pinjaman Saya
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.riwayat') }}" class="text-gray-700 hover:text-primary transition flex items-center gap-2">
                                <i class="fas fa-chevron-right text-xs text-primary"></i>
                                Riwayat Peminjaman
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.profile') }}" class="text-gray-700 hover:text-primary transition flex items-center gap-2">
                                <i class="fas fa-chevron-right text-xs text-primary"></i>
                                Profil Pengguna
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Developer Information -->
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

            <!-- Copyright -->
            <div class="border-t border-gray-300 mt-8 pt-6 text-center text-gray-600 text-sm">
                <p>&copy; 2024 SchoolLend. Sistem Peminjaman Barang Sekolah. Dikembangkan untuk SMAN 1 Cikalongwetan.</p>
            </div>
        </div>
    </footer>
    
    <script>
        // Search function
        function applySearch() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const historyItems = document.querySelectorAll('.history-item');
            let hasResults = false;

            historyItems.forEach(item => {
                const itemSearchText = item.getAttribute('data-search');
                
                if (itemSearchText.includes(searchTerm)) {
                    item.style.display = 'block';
                    hasResults = true;
                } else {
                    item.style.display = 'none';
                }
            });

            // Show/hide no results message
            const noResultsMessage = document.getElementById('noResultsMessage');
            if (!hasResults && searchTerm.length > 0) {
                noResultsMessage.style.display = 'block';
            } else {
                noResultsMessage.style.display = 'none';
            }
        }

        function clearSearch() {
            document.getElementById('searchInput').value = '';
            applySearch();
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Add data-search attribute to all history items
            document.querySelectorAll('.history-item').forEach(item => {
                const title = item.querySelector('h3').textContent.toLowerCase();
                item.setAttribute('data-search', title);
            });
        });
    </script>
</body>
</html>