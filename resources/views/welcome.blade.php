<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SchoolLend - Sistem Peminjaman Barang SMAN 1 Cikalong Wetan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#8B5CF6',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .floating {
            animation: float 6s ease-in-out infinite;
        }
        
        .gradient-text {
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-center justify-between py-4">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-3 text-primary font-bold text-2xl">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-graduation-cap text-lg"></i>
                    </div>
                    <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">SchoolLend</span>
                </a>
                
                <!-- Navigation -->
                <nav class="hidden md:flex items-center gap-2">
                    <a href="#features" class="px-4 py-2 text-gray-600 hover:text-primary hover:bg-primary/10 rounded-xl transition-all">
                        <i class="fas fa-star mr-2"></i>Fitur
                    </a>
                    <a href="#how-it-works" class="px-4 py-2 text-gray-600 hover:text-primary hover:bg-primary/10 rounded-xl transition-all">
                        <i class="fas fa-question-circle mr-2"></i>Cara Kerja
                    </a>
                    <a href="#testimonials" class="px-4 py-2 text-gray-600 hover:text-primary hover:bg-primary/10 rounded-xl transition-all">
                        <i class="fas fa-comments mr-2"></i>Testimoni
                    </a>
                </nav>
                
                <!-- Auth Links -->
                @if (Route::has('login'))
                <nav class="-mx-3 flex flex-1 justify-end">
                    @auth
                    <a
                        href="{{ Auth::user()->hasRole('admin') ? route('admin.dashboard') : route('user.dashboard') }}"
                        class="rounded-md px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white font-medium hover:from-primary/90 hover:to-secondary/90 transition">
                        Dashboard
                    </a>
                    @else
                    <a
                        href="{{ route('login') }}"
                        class="rounded-md px-4 py-2 text-gray-600 hover:text-primary transition">
                        Masuk
                    </a>

                    @if (Route::has('register'))
                    {{-- <a
                        href="{{ route('register') }}"
                        class="rounded-md px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white font-medium hover:from-primary/90 hover:to-secondary/90 transition ml-3">
                        Daftar
                    </a> --}}
                    @endif
                    @endauth
                </nav>
                @endif
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative max-w-7xl mx-auto px-6 py-16 md:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="animate-[fade-in_0.8s_ease]">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                    Sistem Peminjaman Barang <span class="gradient-text bg-gradient-to-r from-primary to-secondary">SMAN 1 Cikalong Wetan</span>
                </h1>
                <p class="text-lg text-gray-600 mb-8">
                    Pinjam peralatan belajar dengan mudah dan efisien. Kelola peminjaman buku, alat tulis, dan peralatan sekolah lainnya dalam satu platform terintegrasi.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('login') }}" class="px-6 py-3 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-xl hover:from-primary/90 hover:to-secondary/90 transition shadow-lg hover:shadow-xl text-center">
                        Mulai Sekarang <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    <a href="#how-it-works" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition text-center">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
                
                <div class="mt-10 flex items-center gap-4">
                    <div class="flex -space-x-2">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold">A</div>
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-white font-bold">B</div>
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-teal-500 flex items-center justify-center text-white font-bold">C</div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Digunakan oleh <span class="font-bold text-gray-900">500+ Siswa</span></p>
                        <div class="flex items-center gap-1">
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <span class="text-sm text-gray-600 ml-1">4.9/5</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="animate-[slide-up_0.8s_ease] relative">
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-gray-100 p-6 floating">
                    <div class="w-full h-64 bg-gradient-to-br from-blue-100 to-purple-100 rounded-2xl flex items-center justify-center">
                        <img src="https://illustrations.popsy.co/amber/digital-nomad.svg" alt="Ilustrasi Peminjaman" class="w-3/4">
                    </div>
                    <div class="mt-6 grid grid-cols-3 gap-4">
                        <div class="bg-white p-3 rounded-xl shadow border border-gray-100">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 mb-2">
                                <i class="fas fa-book"></i>
                            </div>
                            <p class="text-xs font-medium text-gray-600">Buku</p>
                        </div>
                        <div class="bg-white p-3 rounded-xl shadow border border-gray-100">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600 mb-2">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <p class="text-xs font-medium text-gray-600">Alat Tulis</p>
                        </div>
                        <div class="bg-white p-3 rounded-xl shadow border border-gray-100">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center text-green-600 mb-2">
                                <i class="fas fa-microscope"></i>
                            </div>
                            <p class="text-xs font-medium text-gray-600">Lab</p>
                        </div>
                    </div>
                </div>
                
                <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-gradient-to-br from-primary/20 to-secondary/20 rounded-2xl -z-10"></div>
                <div class="absolute -top-8 -right-8 w-20 h-20 bg-gradient-to-br from-yellow-200/30 to-pink-200/30 rounded-full -z-10"></div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="bg-white/70 backdrop-blur-sm py-16 border-y border-gray-200">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Fitur Unggulan SchoolLend</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Platform peminjaman barang sekolah yang dirancang khusus untuk kebutuhan SMAN 1 Cikalong Wetan</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center text-blue-600 mb-6">
                        <i class="fas fa-bolt text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Peminjaman Cepat</h3>
                    <p class="text-gray-600">Proses peminjaman barang hanya dalam hitungan menit dengan antarmuka yang sederhana dan intuitif.</p>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center text-purple-600 mb-6">
                        <i class="fas fa-bell text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Notifikasi Real-time</h3>
                    <p class="text-gray-600">Dapatkan pemberitahuan langsung ketika barang yang dipinjam sudah tersedia atau saat mendekati waktu pengembalian.</p>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center text-green-600 mb-6">
                        <i class="fas fa-chart-line text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Pelacakan Riwayat</h3>
                    <p class="text-gray-600">Catat semua riwayat peminjaman Anda dalam satu tempat untuk memudahkan pelacakan dan evaluasi.</p>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-xl flex items-center justify-center text-yellow-600 mb-6">
                        <i class="fas fa-shield-alt text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Keamanan Data</h3>
                    <p class="text-gray-600">Data pribadi dan transaksi Anda dilindungi dengan sistem keamanan tingkat tinggi.</p>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                    <div class="w-14 h-14 bg-gradient-to-br from-red-100 to-red-200 rounded-xl flex items-center justify-center text-red-600 mb-6">
                        <i class="fas fa-mobile-alt text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Responsif</h3>
                    <p class="text-gray-600">Akses platform dari perangkat apapun, baik smartphone, tablet, maupun komputer.</p>
                </div>
                
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-xl flex items-center justify-center text-indigo-600 mb-6">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Kolaborasi</h3>
                    <p class="text-gray-600">Fitur kelompok memungkinkan peminjaman barang untuk kegiatan bersama dengan mudah.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-16 bg-gradient-to-br from-blue-50 to-purple-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Cara Menggunakan SchoolLend</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Hanya dalam 3 langkah sederhana, Anda bisa meminjam barang yang dibutuhkan</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white font-bold text-xl mb-4">1</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Daftar/Masuk</h3>
                    <p class="text-gray-600">Buat akun atau masuk menggunakan akun yang sudah ada untuk mengakses sistem.</p>
                </div>
                
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white font-bold text-xl mb-4">2</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Cari Barang</h3>
                    <p class="text-gray-600">Temukan barang yang ingin dipinjam melalui fitur pencarian atau kategori.</p>
                </div>
                
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white font-bold text-xl mb-4">3</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Ajukan Peminjaman</h3>
                    <p class="text-gray-600">Isi formulir peminjaman dan tunggu konfirmasi dari petugas perpustakaan/lab.</p>
                </div>
            </div>
            
            {{-- <div class="mt-16 bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    <div class="p-8 lg:p-12">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Video Panduan</h3>
                        <p class="text-gray-600 mb-6">Tonton video berikut untuk memahami cara menggunakan platform SchoolLend dengan lebih baik.</p>
                        <a href="#" class="inline-flex items-center text-primary font-medium hover:text-primary/80 transition">
                            <i class="fas fa-play-circle mr-2"></i> Putar Video Panduan
                        </a>
                    </div>
                    <div class="bg-gray-100 flex items-center justify-center p-8">
                        <div class="w-full h-64 bg-gradient-to-br from-blue-100 to-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-play-circle text-4xl text-primary"></i>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Apa Kata Mereka?</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Testimoni dari siswa dan guru SMAN 1 Cikalong Wetan yang telah menggunakan SchoolLend</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white font-bold">AN</div>
                        <div>
                            <h4 class="font-bold text-gray-900">Ahmad Nur</h4>
                            <p class="text-sm text-gray-600">Siswa Kelas 12 IPA 1</p>
                        </div>
                    </div>
                    <p class="text-gray-600">"Dengan SchoolLend, saya tidak perlu lagi antri panjang untuk meminjam buku pelajaran. Prosesnya cepat dan mudah dipahami."</p>
                    <div class="mt-4 flex gap-1">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                </div>
                
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold">DS</div>
                        <div>
                            <h4 class="font-bold text-gray-900">Dewi Sartika</h4>
                            <p class="text-sm text-gray-600">Guru Matematika</p>
                        </div>
                    </div>
                    <p class="text-gray-600">"Sebagai guru, saya sangat terbantu dengan sistem ini. Sekarang bisa memantau peminjaman alat peraga dengan lebih terstruktur."</p>
                    <div class="mt-4 flex gap-1">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star-half-alt text-yellow-400"></i>
                    </div>
                </div>
                
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-teal-500 rounded-full flex items-center justify-center text-white font-bold">RM</div>
                        <div>
                            <h4 class="font-bold text-gray-900">Rina Mulyani</h4>
                            <p class="text-sm text-gray-600">Siswa Kelas 11 IPS 2</p>
                        </div>
                    </div>
                    <p class="text-gray-600">"Fitur notifikasinya sangat membantu, jadi saya tidak lupa mengembalikan buku yang dipinjam. Aplikasinya juga ringan dan cepat."</p>
                    <div class="mt-4 flex gap-1">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-br from-primary to-secondary">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-white mb-6">Siap Memulai Peminjaman?</h2>
            <p class="text-white/90 max-w-2xl mx-auto mb-8">Bergabunglah dengan ratusan siswa lainnya yang telah merasakan kemudahan dalam meminjam barang sekolah.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="px-6 py-3 bg-white text-primary font-semibold rounded-xl hover:bg-white/90 transition shadow-lg hover:shadow-xl">
                    Daftar Sekarang <i class="fas fa-user-plus ml-2"></i>
                </a>
                <a href="{{ route('login') }}" class="px-6 py-3 border border-white text-white font-medium rounded-xl hover:bg-white/10 transition">
                    Masuk ke Akun
                </a>
            </div>
        </div>
    </section> --}}

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center gap-3 text-white font-bold text-2xl mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-graduation-cap text-lg"></i>
                        </div>
                        <span>SchoolLend</span>
                    </div>
                    <p class="text-gray-400 mb-4">Sistem peminjaman barang terintegrasi untuk SMAN 1 Cikalong Wetan.</p>
                    <div class="flex gap-4">
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Tautan Cepat</h3>
                    <ul class="space-y-2">
                        <li><a href="#features" class="text-gray-400 hover:text-white transition">Fitur</a></li>
                        <li><a href="#how-it-works" class="text-gray-400 hover:text-white transition">Cara Kerja</a></li>
                        <li><a href="#testimonials" class="text-gray-400 hover:text-white transition">Testimoni</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">FAQ</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Jl. Cikalong No.153, Mandalasari, Kab. Bandung Barat</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-phone"></i>
                            <span>(022) 1234567</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-envelope"></i>
                            <span>info@sman1cikalong.sch.id</span>
                        </li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Jam Operasional</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex justify-between">
                            <span>Senin-Jumat</span>
                            <span>07:00 - 15:00</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Sabtu</span>
                            <span>08:00 - 12:00</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Minggu</span>
                            <span>Libur</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                <p>&copy; 2024 SchoolLend - SMAN 1 Cikalong Wetan. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop" class="fixed bottom-6 right-6 w-12 h-12 bg-gradient-to-br from-primary to-secondary rounded-full items-center justify-center text-white shadow-lg hover:shadow-xl transition-all opacity-0 invisible">
    <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Back to Top Button
        const backToTopButton = document.getElementById('backToTop');

        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.remove('opacity-0', 'invisible');
                backToTopButton.classList.add('flex', 'opacity-100', 'visible');
            } else {
                backToTopButton.classList.add('opacity-0', 'invisible');
                backToTopButton.classList.remove('flex', 'opacity-100', 'visible');
            }
        });

        backToTopButton.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        
        backToTopButton.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>

</html>