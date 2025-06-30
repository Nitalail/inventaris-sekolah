<!-- User Footer -->
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
                    <p class="flex items-start gap-2">
                        <i class="fas fa-map-marker-alt text-primary mt-1 flex-shrink-0"></i>
                        <span>Jl. Cikalong No.153, Mandalasari, Kec. Cikalong Wetan, Kabupaten Bandung Barat, Jawa Barat 40556</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-primary"></i>
                        <span>Didirikan: 9 November 1983</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <i class="fas fa-star text-primary"></i>
                        <span>Akreditasi: A</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <i class="fas fa-phone text-primary"></i>
                        <span>(022) 1234567</span>
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
                        <a href="{{ route('user.dashboard') }}" class="text-gray-700 hover:text-primary transition flex items-center gap-2">
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

            <!-- System Information & Developer -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-code text-primary"></i>
                    Tentang Sistem
                </h3>
                <div class="space-y-3">
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
                        Sistem SchoolLend dikembangkan untuk memenuhi kebutuhan manajemen peminjaman barang di SMAN 1 Cikalongwetan dengan teknologi Laravel dan Tailwind CSS.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="text-gray-700 hover:text-primary transition text-xl" title="GitHub">
                            <i class="fab fa-github"></i>
                        </a>
                        <a href="#" class="text-gray-700 hover:text-primary transition text-xl" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-700 hover:text-primary transition text-xl" title="LinkedIn">
                            <i class="fab fa-linkedin"></i>
                        </a>
                        <a href="#" class="text-gray-700 hover:text-primary transition text-xl" title="Email">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-300 mt-8 pt-6">
            <!-- System Stats (Optional) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-primary">{{ \App\Models\Barang::count() }}</div>
                    <div class="text-sm text-gray-600">Total Barang</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-primary">{{ \App\Models\Peminjaman::where('status', 'dipinjam')->count() }}</div>
                    <div class="text-sm text-gray-600">Sedang Dipinjam</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-primary">{{ \App\Models\User::where('role', 'user')->count() }}</div>
                    <div class="text-sm text-gray-600">Pengguna Aktif</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-primary">{{ \App\Models\Kategori::count() }}</div>
                    <div class="text-sm text-gray-600">Kategori</div>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="text-center text-gray-600 text-sm">
                <p>&copy; {{ date('Y') }} SchoolLend. Sistem Peminjaman Barang Sekolah SMAN 1 Cikalongwetan.</p>
                <p class="mt-1">Dikembangkan dengan ❤️ menggunakan Laravel {{ app()->version() }}</p>
            </div>
        </div>
    </div>
</footer> 