<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Siswa - SchoolLend</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="shortcut icon" href="{{ asset('assets/svgs/logo-mark.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#8B5CF6',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'slide-up': 'slideUp 0.5s ease-out'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background: linear-gradient(to bottom right, #f0f9ff, #ffffff, #f5f3ff);
            min-height: 100vh;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .glass-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .avatar-ring {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        /* Style untuk form password */
        .password-form .input-field {
            width: 100%;
            padding: 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            background-color: #f9fafb;
            transition: all 0.3s ease;
        }
        
        .password-form .input-field:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .password-form .input-error {
            border-color: #ef4444;
        }
        
        .password-form .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .password-form .btn-submit {
            background: linear-gradient(to right, #3b82f6, #8b5cf6);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .password-form .btn-submit:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
    </style>
</head>

<body>
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
                    <a href="/user/riwayat" class="px-4 py-2 text-gray-600 hover:text-primary hover:bg-primary/10 rounded-xl transition-all">
                        <i class="fas fa-history mr-2"></i>Riwayat
                    </a>
                    <a href="/user/profile" class="px-4 py-2 text-white bg-gradient-to-r from-primary to-secondary font-semibold rounded-xl shadow-lg">
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
                        <p class="text-sm text-gray-600"> SMAN 1 Cikalong Wetan</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Profil Saya</h1>
            <p class="text-gray-600">Kelola informasi profil dan akun Anda dengan mudah</p>
        </div>

        <!-- Profile Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Profile Sidebar -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Profile Card -->
                <div class="glass-card rounded-2xl shadow-lg border border-gray-100 p-8 text-center animate-fade-in">
                    <div class="relative w-32 h-32 mx-auto mb-6">
                        <div class="absolute inset-0 border-4 border-primary/20 rounded-full avatar-ring"></div>
                        <div class="w-full h-full bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white text-4xl font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}{{ substr(strstr(Auth::user()->name, ' '), 1, 1) }}
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ Auth::user()->name }}</h2>
                    {{-- <p class="text-gray-600 mb-6">Siswa SMAN 1 Jakarta</p> --}}

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-blue-50 rounded-xl p-4 text-center">
                            <p class="text-2xl font-bold text-primary mb-1">3</p>
                            <p class="text-sm text-gray-600">Sedang Dipinjam</p>
                        </div>
                        <div class="bg-purple-50 rounded-xl p-4 text-center">
                            <p class="text-2xl font-bold text-secondary mb-1">15</p>
                            <p class="text-sm text-gray-600">Total Pinjaman</p>
                        </div>
                    </div>

                    <button onclick="openEditProfileModal()" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary to-secondary text-white rounded-xl font-medium hover:from-primary/90 hover:to-secondary/90 transition-all">
                        <i class="fas fa-edit mr-2"></i> Edit Profile
                    </button>
                </div>

                <!-- Quick Actions -->
                <div class="glass-card rounded-2xl shadow-lg border border-gray-100 p-6 animate-fade-in">
                    <h3 class="font-semibold text-gray-800 mb-6 text-lg">Aksi Cepat</h3>
                    <div class="space-y-4">
                        <a href="/user/pinjaman-saya" class="flex items-center gap-4 p-4 rounded-xl hover:bg-gray-50 transition-all">
                            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-book"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Pinjaman Aktif</p>
                                <p class="text-sm text-gray-500">Lihat barang yang sedang dipinjam</p>
                            </div>
                        </a>
                        <a href="/user/riwayat" class="flex items-center gap-4 p-4 rounded-xl hover:bg-gray-50 transition-all">
                            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-history"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Riwayat Pinjaman</p>
                                <p class="text-sm text-gray-500">Lihat histori peminjaman</p>
                            </div>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-4 p-4 rounded-xl hover:bg-gray-50 transition-all text-left">
                                <div class="w-12 h-12 bg-red-100 text-red-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-sign-out-alt"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Keluar</p>
                                    <p class="text-sm text-gray-500">Logout dari akun</p>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="lg:col-span-8">
                <!-- Personal Information -->
                <div class="glass-card rounded-2xl shadow-lg border border-gray-100 p-8 animate-slide-up">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                            <i class="fas fa-user-circle text-primary"></i>
                            Informasi Pribadi
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Nama Lengkap</label>
                            <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Peran</label>
                            <p class="text-lg font-semibold text-gray-800">
                                @if(Auth::user()->role == 'admin')
                                    Administrator
                                @else
                                    Pengguna
                                @endif
                            </p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Email</label>
                            <p class="text-lg font-semibold text-gray-800">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-500 uppercase tracking-wide">Tanggal Bergabung</label>
                            <p class="text-lg font-semibold text-gray-800">
                                {{ Auth::user()->created_at->format('d F Y') }}
                            </p>
                        </div>
                    </div> 
                </div> 

                <!-- Change Password Section -->
                <div class="glass-card rounded-2xl shadow-lg border border-gray-100 p-8 mt-8 animate-slide-up">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <i class="fas fa-lock text-primary"></i>
                        Ubah Password
                    </h2>

                    <!-- Include Update Password Form -->
                    <div class="password-form">
                        <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                            @csrf
                            @method('put')

                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                                <input id="current_password" name="current_password" type="password" class="input-field @error('current_password') input-error @enderror" autocomplete="current-password">
                                @error('current_password')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                <input id="password" name="password" type="password" class="input-field @error('password') input-error @enderror" autocomplete="new-password">
                                @error('password')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="input-field" autocomplete="new-password">
                            </div>

                            <div class="flex items-center gap-4">
                                <button type="submit" class="btn-submit">
                                    Simpan Perubahan
                                </button>

                                @if (session('status') === 'password-updated')
                                    <p x-data="{ show: true }" 
                                       x-show="show" 
                                       x-transition 
                                       x-init="setTimeout(() => show = false, 2000)" 
                                       class="text-sm text-green-600">
                                        Password berhasil diubah!
                                    </p>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Edit Profile Modal -->
    <div id="editProfileModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 invisible opacity-0 transition-all duration-300">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditProfileModal()"></div>
        
        <div class="relative glass-card rounded-2xl shadow-2xl border border-gray-200/70 w-full max-w-md transform scale-95 transition-all duration-300">
            <div class="px-6 py-4 border-b border-gray-200/70 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Edit Profile Information</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-slow" onclick="closeEditProfileModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Profile Information Form -->
            <div class="p-6">
                <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" id="name" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" 
                               value="{{ old('name', Auth::user()->name) }}" required autofocus autocomplete="name">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" id="email" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-slow" 
                               value="{{ old('email', Auth::user()->email) }}" required autocomplete="username">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    @if (session('status') === 'profile-updated')
                        <p x-data="{ show: true }" 
                           x-show="show" 
                           x-transition 
                           x-init="setTimeout(() => show = false, 2000)" 
                           class="text-sm text-green-600">
                            Profile berhasil diupdate!
                        </p>
                    @endif

                    <div class="flex items-center justify-end gap-4">
                        <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow" onclick="closeEditProfileModal()">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-lg hover:from-primary/90 hover:to-secondary/90 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-slow shadow-sm hover:shadow-md">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
        // Modal functions with smooth animations
        function openEditProfileModal() {
            const modal = document.getElementById('editProfileModal');
            modal.classList.remove('invisible', 'opacity-0');
            modal.classList.add('visible', 'opacity-100');
            setTimeout(() => {
                modal.querySelector('.glass-card').classList.remove('scale-95');
                modal.querySelector('.glass-card').classList.add('scale-100');
            }, 10);
        }

        function closeEditProfileModal() {
            const modal = document.getElementById('editProfileModal');
            modal.querySelector('.glass-card').classList.remove('scale-100');
            modal.querySelector('.glass-card').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.remove('visible', 'opacity-100');
                modal.classList.add('invisible', 'opacity-0');
            }, 200);
        }

        // Enhanced toast notification with smooth animations
        function showToast(message, type = 'info') {
            const toast = document.getElementById('toast');
            const toastIcon = document.getElementById('toast-icon');
            const toastTitle = document.getElementById('toast-title');
            const toastMessage = document.getElementById('toast-message');

            // Set icon and colors based on type
            let iconClass, iconColor;
            switch (type) {
                case 'success':
                    iconClass = 'fa-check-circle';
                    iconColor = 'text-green-500';
                    toastTitle.textContent = 'Sukses';
                    break;
                case 'error':
                    iconClass = 'fa-times-circle';
                    iconColor = 'text-red-500';
                    toastTitle.textContent = 'Error';
                    break;
                case 'warning':
                    iconClass = 'fa-exclamation-triangle';
                    iconColor = 'text-yellow-500';
                    toastTitle.textContent = 'Peringatan';
                    break;
                default:
                    iconClass = 'fa-info-circle';
                    iconColor = 'text-blue-500';
                    toastTitle.textContent = 'Info';
            }

            // Update toast content
            toastMessage.textContent = message;
            toastIcon.className = `fas ${iconClass} ${iconColor} mt-1`;

            // Show toast with smooth animation
            toast.classList.remove('translate-x-full', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');

            // Auto hide after 5 seconds
            setTimeout(hideToast, 5000);
        }

        function hideToast() {
            const toast = document.getElementById('toast');
            toast.classList.remove('translate-x-0', 'opacity-100');
            toast.classList.add('translate-x-full', 'opacity-0');
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.id === 'editProfileModal') {
                closeEditProfileModal();
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEditProfileModal();
                hideToast();
            }
        });

        // Add smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add loading state to buttons
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
                    submitBtn.disabled = true;

                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 1500);
                }
            });
        });
    </script>
</body>
</html>