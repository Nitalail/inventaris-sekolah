<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Siswa - SchoolLend</title>
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
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .skeleton {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            background-color: #e2e8f0;
            border-radius: 0.5rem;
        }
        
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
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
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 text-white bg-gradient-to-r from-primary to-secondary font-semibold rounded-xl shadow-lg">
                        <i class="fas fa-home mr-2"></i>Beranda
                    </a>
                    <a href="{{ route('user.pinjaman-saya') }}" class="px-4 py-2 text-gray-600 hover:text-primary hover:bg-primary/10 rounded-xl transition-all">
                        <i class="fas fa-book-open mr-2"></i>Pinjaman 
                    </a>
                    <a href="{{ route('user.riwayat') }}" class="px-4 py-2 text-gray-600 hover:text-primary hover:bg-primary/10 rounded-xl transition-all">
                        <i class="fas fa-history mr-2"></i>Riwayat
                    </a>
                    <a href="{{ route('user.profile') }}" class="px-4 py-2 text-gray-600 hover:text-primary hover:bg-primary/10 rounded-xl transition-all">
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
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang</h1>
            <p class="text-gray-600">Temukan dan pinjam barang yang Anda butuhkan untuk kegiatan belajar.</p>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Pinjaman Aktif -->
            <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center text-blue-600">
                        <i class="fas fa-book text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Pinjaman Aktif</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['activeLoans'] }} Item</p>
                    </div>
                </div>
            </div>
            
            <!-- Total Riwayat -->
            <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center text-purple-600">
                        <i class="fas fa-history text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Total peminjaman</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['totalHistory'] }} Transaksi</p>
                    </div>
                </div>
            </div>
            
            <!-- Selesai -->
            <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center text-green-600">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Selesai</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['completedLoans'] }} Transaksi</p>
                    </div>
                </div>
            </div>
            
            <!-- Keterlambatan -->
            <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-100 to-red-200 rounded-xl flex items-center justify-center text-red-600">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Keterlambatan</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['overdueLoans'] }} Item</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Section -->
        <div class="bg-white/70 backdrop-blur-sm rounded-3xl shadow-lg border border-gray-100 p-8 mb-8">
            <div class="flex items-center gap-3 mb-5">
                <i class="fas fa-search text-primary"></i>
                <h2 class="text-xl font-semibold text-gray-900">Cari Barang</h2>
            </div>
            
            <form class="flex flex-col sm:flex-row gap-3 mb-4" onsubmit="searchItems(event)">
                <input type="text" class="flex-1 min-w-0 px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition" placeholder="Cari buku, alat tulis, atau peralatan lainnya..." id="searchInput">
                <select class="px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition min-w-[150px]" id="categorySelect">
                    <option value="">Semua Kategori</option>
                    <option value="buku">Buku</option>
                    <option value="alat-tulis">Alat Tulis</option>
                    <option value="elektronik">Elektronik</option>
                    <option value="olahraga">Olahraga</option>
                </select>
                <button type="submit" class="bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white px-6 py-3 rounded-xl font-semibold flex items-center justify-center gap-2 transition hover:-translate-y-0.5 hover:shadow">
                    <i class="fas fa-search"></i>
                    Cari
                </button>
            </form>
            
            <div class="flex flex-wrap gap-2">
                <span class="filter-tag active bg-primary/10 text-primary px-3 py-1 rounded-full text-xs font-medium cursor-pointer transition hover:bg-primary/20" onclick="toggleFilter(this, 'semua')">Semua</span>
                <span class="filter-tag bg-primary/10 text-primary px-3 py-1 rounded-full text-xs font-medium cursor-pointer transition hover:bg-primary/20" onclick="toggleFilter(this, 'tersedia')">Tersedia</span>
                <span class="filter-tag bg-primary/10 text-primary px-3 py-1 rounded-full text-xs font-medium cursor-pointer transition hover:bg-primary/20" onclick="toggleFilter(this, 'terbatas')">Stok Terbatas</span>
                <span class="filter-tag bg-primary/10 text-primary px-3 py-1 rounded-full text-xs font-medium cursor-pointer transition hover:bg-primary/20" onclick="toggleFilter(this, 'populer')">Populer</span>
                <span class="filter-tag bg-primary/10 text-primary px-3 py-1 rounded-full text-xs font-medium cursor-pointer transition hover:bg-primary/20" onclick="toggleFilter(this, 'baru')">Baru</span>
            </div>
        </div>

        <!-- Items Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($barangTersedia as $barang)
            <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 relative">
                <div class="w-full h-40 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl mb-4 flex items-center justify-center overflow-hidden">
                    @php
                        $categoryLower = strtolower($barang->kategori->nama ?? 'default');
                        $iconMapping = [
                            'buku' => 'fas fa-book text-blue-500',
                            'elektronik' => 'fas fa-calculator text-purple-500',
                            'alat tulis' => 'fas fa-pencil-alt text-amber-500',
                            'olahraga' => 'fas fa-volleyball-ball text-emerald-500',
                            'laboratorium' => 'fas fa-microscope text-indigo-500',
                        ];
                        $icon = $iconMapping[$categoryLower] ?? 'fas fa-box text-gray-500';
                    @endphp
                    <i class="{{ $icon }} text-4xl"></i>
                </div>

                <h3 class="text-lg font-bold mb-2 text-gray-900">{{ $barang->nama }}</h3>
                <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                    {{ $barang->deskripsi ?? 'Tidak ada deskripsi tersedia.' }}
                </p>
                
                @php
                    $jumlah = $barang->jumlah;
                    if ($jumlah == 0) {
                        $statusClass = 'bg-red-50 text-red-700';
                        $statusText = 'Tidak Tersedia';
                        $btnDisabled = 'disabled';
                    } elseif ($jumlah <= 5) {
                        $statusClass = 'bg-amber-50 text-amber-700';
                        $statusText = 'Stok Sedikit ('.$jumlah.')';
                        $btnDisabled = '';
                    } else {
                        $statusClass = 'bg-green-50 text-green-700';
                        $statusText = 'Tersedia ('.$jumlah.')';
                        $btnDisabled = '';
                    }
                @endphp

                <div class="flex items-center justify-between mb-4">
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                        {{ $statusText }}
                    </span>
                    <span class="text-xs text-gray-500">{{ $barang->kategori->nama ?? 'Umum' }}</span>
                </div>

                <button class="w-full bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white py-3 px-4 rounded-lg font-semibold transition-all flex items-center justify-center gap-2 {{ $btnDisabled ? 'opacity-50 cursor-not-allowed' : 'hover:-translate-y-0.5 hover:shadow' }}" 
                    {{ $btnDisabled }} 
                    onclick="openBorrowModal('{{ $barang->id }}', '{{ $barang->nama }}', '{{ $barang->deskripsi }}', '{{ $barang->kondisi }}')">
                    <i class="fas fa-hand-holding"></i>
                    Pinjam
                </button>
            </div>
            @endforeach
        </div>
    </main>

    <!-- Borrow Modal -->
    <div class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 backdrop-blur-sm" id="borrowModal">
        <div class="modal-content bg-white rounded-xl p-8 max-w-md w-full max-h-[80vh] overflow-y-auto animate-[slideUp_0.3s_ease]">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Pinjam Barang</h2>
                <button class="text-gray-600 text-2xl hover:text-primary transition transform hover:rotate-90" onclick="closeBorrowModal()">&times;</button>
            </div>

            <div id="borrowModalContent"></div>
        </div>
    </div>

    <!-- Notification Modal -->
    <div class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 backdrop-blur-sm" id="notificationModal">
        <div class="modal-content bg-white rounded-xl p-6 max-w-2xl w-full max-h-[80vh] overflow-y-auto animate-[slideUp_0.3s_ease]">
            <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <i class="fas fa-bell text-primary text-2xl"></i>
                    <h2 class="text-2xl font-bold text-gray-900">Notifikasi</h2>
                </div>
                <div class="flex gap-3">
                    <button class="bg-primary/10 text-primary px-4 py-2 rounded-lg font-medium flex items-center gap-2 hover:bg-primary/20 transition" onclick="markAllAsRead()">
                        <i class="fas fa-check-double"></i>
                        Tandai Semua Dibaca
                    </button>
                    <button class="text-gray-600 text-2xl hover:text-primary transition" onclick="closeNotificationModal()">&times;</button>
                </div>
            </div>
            
            <div class="space-y-3 pr-2 max-h-[60vh] overflow-y-auto" id="notificationsList"></div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="fixed top-5 right-5 z-50 space-y-3"></div>

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
        // Inisialisasi variabel
        let currentFilter = 'semua';
        let notifications = [
            {
                id: 1,
                type: 'reminder',
                title: 'Pengingat Pengembalian',
                message: 'Buku "Matematika Dasar" harus dikembalikan besok',
                time: '2 jam yang lalu',
                read: false
            },
            {
                id: 2,
                type: 'success',
                title: 'Peminjaman Disetujui',
                message: 'Permintaan peminjaman kalkulator telah disetujui',
                time: '1 hari yang lalu',
                read: false
            }
        ];

        // Fungsi untuk toggle filter
        function toggleFilter(element, filterType) {
            document.querySelectorAll('.filter-tag').forEach(tag => {
                tag.classList.remove('active', 'bg-primary/20');
            });
            
            element.classList.add('active', 'bg-primary/20');
            currentFilter = filterType;
            applyFilter(filterType);
        }

        // Fungsi untuk menerapkan filter
        function applyFilter(filterType) {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const category = document.getElementById('categorySelect').value.toLowerCase();
            const itemCards = document.querySelectorAll('.bg-white\\/70.backdrop-blur-sm.rounded-2xl');
            
            let visibleCount = 0;

            itemCards.forEach(card => {
                const title = card.querySelector('h3').textContent.toLowerCase();
                const description = card.querySelector('p').textContent.toLowerCase();
                const cardCategory = card.querySelector('span.text-xs.text-gray-500').textContent.toLowerCase();
                const statusElement = card.querySelector('span.px-2\\.5.py-1.rounded-full');
                const statusText = statusElement ? statusElement.textContent.toLowerCase() : '';

                // Cek kecocokan dengan pencarian
                const matchesSearch = searchTerm === '' || 
                                    title.includes(searchTerm) || 
                                    description.includes(searchTerm);
                
                // Cek kecocokan dengan kategori
                const matchesCategory = category === '' || 
                                       cardCategory.includes(category);
                
                // Cek kecocokan dengan filter
                let matchesFilter = true;
                switch(filterType) {
                    case 'tersedia':
                        matchesFilter = !statusText.includes('tidak tersedia');
                        break;
                    case 'terbatas':
                        matchesFilter = statusText.includes('stok sedikit');
                        break;
                    case 'populer':
                        matchesFilter = card.dataset.popular === 'true';
                        break;
                    case 'baru':
                        matchesFilter = card.dataset.new === 'true';
                        break;
                    case 'semua':
                    default:
                        matchesFilter = true;
                }

                // Tampilkan/sembunyikan card berdasarkan hasil filter
                if (matchesSearch && matchesCategory && matchesFilter) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            updateEmptyState(visibleCount === 0);
        }

        // Fungsi untuk menangani pencarian
        function searchItems(event) {
            event.preventDefault();
            applyFilter(currentFilter);
        }

        // Fungsi untuk mengupdate empty state
        function updateEmptyState(isEmpty) {
            const itemsContainer = document.querySelector('.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3.xl\\:grid-cols-4');
            const existingEmptyState = itemsContainer.querySelector('.empty-state');
            
            if (isEmpty && !existingEmptyState) {
                const emptyState = document.createElement('div');
                emptyState.className = 'empty-state col-span-full py-12 flex flex-col items-center animate-[fadeIn_0.5s_ease]';
                emptyState.innerHTML = `
                    <div class="w-20 h-20 rounded-full bg-gray-50 flex items-center justify-center mb-4">
                        <i class="fas fa-box-open text-gray-300 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">Tidak ada barang ditemukan</h3>
                    <p class="text-gray-400 text-sm mb-5">Coba ubah filter atau kata kunci pencarian Anda</p>
                    <button class="px-4 py-2 bg-primary text-white text-sm font-medium rounded-xl hover:bg-primary/90 transition-colors" 
                            onclick="resetFilters()">
                        Reset Filter
                    </button>
                `;
                itemsContainer.appendChild(emptyState);
            } else if (!isEmpty && existingEmptyState) {
                existingEmptyState.remove();
            }
        }

        // Fungsi untuk mereset semua filter
        function resetFilters() {
            // Reset UI filter tags
            document.querySelectorAll('.filter-tag').forEach(tag => {
                tag.classList.remove('active', 'bg-primary/20');
            });
            
            // Aktifkan filter 'semua'
            const allFilter = document.querySelector('.filter-tag[onclick*="semua"]');
            if (allFilter) {
                allFilter.classList.add('active', 'bg-primary/20');
            }
            
            // Reset input pencarian
            document.getElementById('searchInput').value = '';
            document.getElementById('categorySelect').value = '';

            // Reset current filter dan apply
            currentFilter = 'semua';
            applyFilter('semua');
        }

        // Fungsi untuk modal pinjaman
        function openBorrowModal(id, name, description, condition) {
            const modal = document.getElementById('borrowModal');
            const modalContent = document.getElementById('borrowModalContent');
            
            modalContent.innerHTML = `
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2 text-gray-900">${name}</h3>
                    <p class="text-gray-600 mb-3">${description}</p>
                    <p class="text-sm font-semibold px-3 py-1.5 bg-gray-50 rounded-lg border-l-4 border-primary">Kondisi: ${condition}</p>
                </div>
                
                <form id="borrowForm" onsubmit="submitBorrow(event, '${id}')">
                    <div class="mb-5">
                        <label class="block font-semibold mb-2 text-gray-900">Jumlah yang Dipinjam</label>
                        <input type="number" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition" id="borrowQuantity" min="1" max="5" value="1" required>
                    </div>
                    
                    <div class="mb-5">
                        <label class="block font-semibold mb-2 text-gray-900">Tanggal Mulai Pinjam</label>
                        <input type="date" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition" id="borrowStartDate" required>
                    </div>
                    
                    <div class="mb-5">
                        <label class="block font-semibold mb-2 text-gray-900">Tanggal Kembali</label>
                        <input type="date" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition" id="borrowEndDate" required>
                    </div>
                    
                    <div class="mb-5">
                        <label class="block font-semibold mb-2 text-gray-900">Keperluan</label>
                        <textarea class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition min-h-[100px]" id="borrowPurpose" placeholder="Jelaskan untuk apa barang ini digunakan..." required></textarea>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3 justify-end">
                        <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-900 px-6 py-3 rounded-xl font-semibold transition" onclick="closeBorrowModal()">Batal</button>
                        <button type="submit" class="bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 text-white px-6 py-3 rounded-xl font-semibold transition hover:-translate-y-0.5 hover:shadow flex items-center gap-2">
                            <i class="fas fa-paper-plane"></i> Ajukan Peminjaman
                        </button>
                    </div>
                </form>
            `;
            
            modal.style.display = 'flex';
            setupDateConstraints();
        }

        function closeBorrowModal() {
            document.getElementById('borrowModal').style.display = 'none';
        }

        // Fungsi untuk submit pinjaman
        function submitBorrow(event, itemId) {
            event.preventDefault();
            
            const quantity = document.getElementById('borrowQuantity').value;
            const startDate = document.getElementById('borrowStartDate').value;
            const endDate = document.getElementById('borrowEndDate').value;
            const purpose = document.getElementById('borrowPurpose').value;
            
            if (!quantity || !startDate || !endDate || !purpose) {
                showToast('error', 'Semua field harus diisi!');
                return;
            }
            
            if (new Date(startDate) >= new Date(endDate)) {
                showToast('error', 'Tanggal kembali harus setelah tanggal mulai pinjam!');
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Show loading state
            const submitBtn = event.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner animate-spin"></i> Mengirim...';
            submitBtn.disabled = true;

            fetch('/user/peminjaman/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    barangId: itemId,
                    quantity: parseInt(quantity),
                    startDate: startDate,
                    endDate: endDate,
                    purpose: purpose
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                showToast('success', 'Permintaan peminjaman berhasil diajukan!');
                closeBorrowModal();
                
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            })
            .catch(error => {
                showToast('error', error.message || 'Terjadi kesalahan saat mengajukan peminjaman');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }

        // Fungsi untuk notifikasi
        function openNotificationModal() {
            const modal = document.getElementById('notificationModal');
            const notificationsList = document.getElementById('notificationsList');
            
            notificationsList.innerHTML = notifications.map(notification => `
                <div class="flex gap-4 p-4 rounded-xl border border-gray-200 hover:border-primary hover:bg-primary/10 transition cursor-pointer relative ${notification.read ? 'bg-white' : 'bg-primary/10 border-l-4 border-primary'}" 
                     onclick="markAsRead(${notification.id})">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full ${getNotificationColor(notification.type)} flex items-center justify-center">
                        <i class="fas ${getNotificationIcon(notification.type)}"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold mb-1 text-gray-900">${notification.title}</h4>
                        <p class="text-xs text-gray-600 mb-2">${notification.message}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">${notification.time}</span>
                            ${!notification.read ? '<span class="absolute top-4 right-4 w-2 h-2 bg-primary rounded-full"></span>' : ''}
                        </div>
                    </div>
                </div>
            `).join('');
            
            modal.style.display = 'flex';
            updateNotificationBadge();
        }

        function closeNotificationModal() {
            document.getElementById('notificationModal').style.display = 'none';
        }

        function getNotificationIcon(type) {
            const icons = {
                reminder: 'fa-clock',
                success: 'fa-check-circle',
                info: 'fa-info-circle',
                warning: 'fa-exclamation-triangle',
                error: 'fa-times-circle'
            };
            return icons[type] || 'fa-bell';
        }

        function getNotificationColor(type) {
            const colors = {
                reminder: 'bg-blue-100 text-blue-600',
                success: 'bg-green-100 text-green-600',
                info: 'bg-blue-100 text-blue-600',
                warning: 'bg-amber-100 text-amber-600',
                error: 'bg-red-100 text-red-600'
            };
            return colors[type] || 'bg-blue-100 text-blue-600';
        }

        function markAsRead(notificationId) {
            const notification = notifications.find(n => n.id === notificationId);
            if (notification && !notification.read) {
                notification.read = true;
                updateNotificationBadge();
                
                // Update UI
                const notificationElement = document.querySelector(`[onclick="markAsRead(${notificationId})"]`);
                if (notificationElement) {
                    notificationElement.classList.remove('bg-primary/10', 'border-l-4', 'border-primary');
                    notificationElement.classList.add('bg-white');
                    
                    const unreadBadge = notificationElement.querySelector('.absolute');
                    if (unreadBadge) {
                        unreadBadge.remove();
                    }
                }
            }
        }

        function markAllAsRead() {
            notifications.forEach(notification => {
                notification.read = true;
            });
            
            updateNotificationBadge();
            
            // Update all notification elements in the modal
            document.querySelectorAll('#notificationsList > div').forEach(element => {
                element.classList.remove('bg-primary/10', 'border-l-4', 'border-primary');
                element.classList.add('bg-white');
                
                const unreadBadge = element.querySelector('.absolute');
                if (unreadBadge) {
                    unreadBadge.remove();
                }
            });
        }

        function updateNotificationBadge() {
            const unreadCount = notifications.filter(n => !n.read).length;
            const badge = document.getElementById('notificationBadge');
            if (badge) {
                badge.textContent = unreadCount;
                badge.style.display = unreadCount > 0 ? 'flex' : 'none';
            }
        }

        // Fungsi untuk toast
        function showToast(type, message) {
            const toastContainer = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            
            const toastClasses = {
                success: 'bg-green-500 text-white',
                error: 'bg-red-500 text-white',
                info: 'bg-blue-500 text-white'
            };
            
            toast.className = `flex items-center gap-3 p-4 rounded-xl shadow-lg max-w-xs transition-all transform translate-x-full opacity-0 ${toastClasses[type]}`;
            
            const icon = type === 'success' ? 'fa-check-circle' : 
                        type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
            
            toast.innerHTML = `
                <i class="fas ${icon} text-xl"></i>
                <div class="flex-1 text-sm">${message}</div>
                <button class="toast-close text-lg">&times;</button>
            `;
            
            toastContainer.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('translate-x-0', 'opacity-100');
            }, 100);
            
            toast.querySelector('.toast-close').addEventListener('click', () => {
                toast.remove();
            });
            
            setTimeout(() => {
                toast.remove();
            }, 5000);
        }

        // Fungsi untuk setup tanggal
        function setupDateConstraints() {
            const today = new Date().toISOString().split('T')[0];
            const startDateInputs = document.querySelectorAll('#borrowStartDate');
            
            startDateInputs.forEach(input => {
                input.min = today;
                input.addEventListener('change', function() {
                    const correspondingEndDate = document.getElementById('borrowEndDate');
                    
                    if (correspondingEndDate) {
                        const nextDay = new Date(this.value);
                        nextDay.setDate(nextDay.getDate() + 1);
                        correspondingEndDate.min = nextDay.toISOString().split('T')[0];
                        
                        if (correspondingEndDate.value && new Date(correspondingEndDate.value) < nextDay) {
                            correspondingEndDate.value = nextDay.toISOString().split('T')[0];
                        }
                    }
                });
            });
        }

        // Fungsi untuk navigasi
        function showMyBorrows() {
            window.location.href = "{{ route('user.pinjaman-saya') }}";
        }

        function showHistory() {
            window.location.href = "{{ route('user.riwayat') }}";
        }

        function showProfile() {
            window.location.href = "{{ route('user.profile') }}";
        }

        function logout() {
            if (confirm('Apakah Anda yakin ingin logout?')) {
                window.location.href = '/logout';
            }
        }

        // Event listener untuk modals
        document.addEventListener('click', function(event) {
            const modals = ['notificationModal', 'borrowModal'];
            
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal && event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modals = ['notificationModal', 'borrowModal'];
                
                modals.forEach(modalId => {
                    const modal = document.getElementById(modalId);
                    if (modal && modal.style.display === 'flex') {
                        modal.style.display = 'none';
                    }
                });
            }
        });

        // Inisialisasi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            setupDateConstraints();
            updateNotificationBadge();
        });
    </script>

    
</body>
</html>