<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SchoolLend - Platform Peminjaman Barang Sekolah Modern</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.6;
            color: #1a202c;
            overflow-x: hidden;
        }
        
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }
        
        /* Header */
        .header {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 0;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            font-weight: 700;
            font-size: 24px;
            color: #2563eb;
        }
        
        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }
        
        .nav-links {
            display: flex;
            gap: 32px;
            list-style: none;
        }
        
        .nav-links a {
            text-decoration: none;
            color: #64748b;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .nav-links a:hover {
            color: #2563eb;
        }
        
        .auth-buttons {
            display: flex;
            gap: 12px;
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-outline {
            background: transparent;
            color: #2563eb;
            border: 2px solid #e2e8f0;
        }
        
        .btn-outline:hover {
            border-color: #2563eb;
            background: #f8fafc;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4);
        }
        
        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="1" fill="rgba(59,130,246,0.1)"/></svg>') repeat;
            background-size: 50px 50px;
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .hero-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
            position: relative;
            z-index: 2;
            padding: 120px 0 80px;
        }
        
        .hero-text h1 {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 24px;
            background: linear-gradient(135deg, #1e293b, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .hero-text p {
            font-size: 1.25rem;
            color: #64748b;
            margin-bottom: 32px;
            line-height: 1.7;
        }
        
        .hero-buttons {
            display: flex;
            gap: 16px;
            margin-bottom: 48px;
        }
        
        .btn-hero {
            padding: 16px 32px;
            font-size: 16px;
            border-radius: 12px;
        }
        
        .hero-stats {
            display: flex;
            gap: 32px;
        }
        
        .stat {
            text-align: center;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: #2563eb;
            display: block;
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: #64748b;
            font-weight: 500;
        }
        
        .hero-image {
            position: relative;
        }
        
        .hero-main-image {
            width: 100%;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: rotate(2deg);
            transition: transform 0.3s ease;
        }
        
        .hero-main-image:hover {
            transform: rotate(0deg) scale(1.02);
        }
        
        .floating-card {
            position: absolute;
            background: white;
            padding: 16px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            animation: floatCard 4s ease-in-out infinite;
        }
        
        @keyframes floatCard {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        
        .card-1 {
            top: 20px;
            right: -20px;
            animation-delay: -1s;
        }
        
        .card-2 {
            bottom: 60px;
            left: -30px;
            animation-delay: -2s;
        }
        
        .card-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            margin-bottom: 8px;
        }
        
        .card-text {
            font-size: 12px;
            font-weight: 600;
            color: #1e293b;
        }
        
        /* Features Section */
        .features {
            padding: 120px 0;
            background: white;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 80px;
        }
        
        .section-badge {
            display: inline-block;
            padding: 8px 16px;
            background: rgba(59, 130, 246, 0.1);
            color: #2563eb;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 16px;
        }
        
        .section-title {
            font-size: 3rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 16px;
        }
        
        .section-subtitle {
            font-size: 1.25rem;
            color: #64748b;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 32px;
        }
        
        .feature-card {
            background: white;
            padding: 40px;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            border-color: rgba(59, 130, 246, 0.2);
        }
        
        .feature-card:hover::before {
            transform: scaleX(1);
        }
        
        .feature-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 24px;
        }
        
        .icon-blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; }
        .icon-green { background: linear-gradient(135deg, #10b981, #059669); color: white; }
        .icon-purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white; }
        .icon-orange { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; }
        .icon-red { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; }
        .icon-teal { background: linear-gradient(135deg, #14b8a6, #0d9488); color: white; }
        
        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 12px;
        }
        
        .feature-description {
            color: #64748b;
            line-height: 1.6;
        }
        
        /* How It Works */
        .how-it-works {
            padding: 120px 0;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin-top: 80px;
        }
        
        .step-card {
            text-align: center;
            position: relative;
        }
        
        .step-number {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: 800;
            margin: 0 auto 24px;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
        }
        
        .step-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 12px;
        }
        
        .step-description {
            color: #64748b;
            line-height: 1.6;
        }
        
        .step-image {
            width: 100%;
            height: 200px;
            background: white;
            border-radius: 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .step-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        /* Testimonials */
        .testimonials {
            padding: 120px 0;
            background: white;
        }
        
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 32px;
            margin-top: 80px;
        }
        
        .testimonial-card {
            background: #f8fafc;
            padding: 32px;
            border-radius: 24px;
            border-left: 4px solid #3b82f6;
            position: relative;
        }
        
        .testimonial-text {
            font-size: 1.125rem;
            color: #334155;
            font-style: italic;
            margin-bottom: 24px;
            line-height: 1.7;
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .author-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 18px;
        }
        
        .author-info h4 {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }
        
        .author-info p {
            color: #64748b;
            font-size: 14px;
        }
        
        /* School Gallery */
        .school-gallery {
            padding: 120px 0;
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: white;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 80px;
        }
        
        .gallery-item {
            aspect-ratio: 1;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .gallery-item:hover {
            transform: scale(1.05);
        }
        
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.8), rgba(139, 92, 246, 0.8));
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            text-align: center;
            padding: 20px;
        }
        
        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }
        
        /* CTA Section */
        .cta {
            padding: 120px 0;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><polygon points="50,0 100,50 50,100 0,50" fill="rgba(255,255,255,0.05)"/></svg>') repeat;
            background-size: 100px 100px;
            animation: float 8s ease-in-out infinite;
        }
        
        .cta-content {
            position: relative;
            z-index: 2;
        }
        
        .cta h2 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 24px;
        }
        
        .cta p {
            font-size: 1.25rem;
            margin-bottom: 40px;
            opacity: 0.9;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .cta-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-white {
            background: white;
            color: #2563eb;
            font-weight: 700;
        }
        
        .btn-white:hover {
            background: #f8fafc;
            transform: translateY(-2px);
        }
        
        .btn-outline-white {
            background: transparent;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-outline-white:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
        }
        
        /* Footer */
        .footer {
            padding: 80px 0 40px;
            background: #0f172a;
            color: white;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 60px;
            margin-bottom: 60px;
        }
        
        .footer-brand h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 16px;
            color: white;
        }
        
        .footer-brand p {
            color: #94a3b8;
            line-height: 1.7;
            margin-bottom: 24px;
        }
        
        .social-links {
            display: flex;
            gap: 16px;
        }
        
        .social-link {
            width: 40px;
            height: 40px;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3b82f6;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .social-link:hover {
            background: #3b82f6;
            color: white;
            transform: translateY(-2px);
        }
        
        .footer-section h4 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 24px;
            color: white;
        }
        
        .footer-section ul {
            list-style: none;
        }
        
        .footer-section ul li {
            margin-bottom: 12px;
        }
        
        .footer-section ul li a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-section ul li a:hover {
            color: #3b82f6;
        }
        
        .footer-bottom {
            border-top: 1px solid #1e293b;
            padding-top: 40px;
            text-align: center;
            color: #94a3b8;
        }
        
        /* Mobile Menu */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: #64748b;
            cursor: pointer;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .hero-content {
                grid-template-columns: 1fr;
                gap: 60px;
                text-align: center;
            }
            
            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .footer-content {
                grid-template-columns: 1fr 1fr;
                gap: 40px;
            }
        }
        
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .hero-text h1 {
                font-size: 2.5rem;
            }
            
            .section-title {
                font-size: 2.25rem;
            }
            
            .cta h2 {
                font-size: 2.25rem;
            }
            
            .hero-stats {
                justify-content: center;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .gallery-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
            }
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 0 16px;
            }
            
            .hero-text h1 {
                font-size: 2rem;
            }
            
            .auth-buttons {
                flex-direction: column;
                gap: 8px;
            }
            
            .nav {
                flex-direction: column;
                gap: 16px;
            }
        }
        
        /* Animations */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-in {
            animation: slideInUp 0.6s ease-out forwards;
        }
        
        .animate-delay-1 { animation-delay: 0.1s; }
        .animate-delay-2 { animation-delay: 0.2s; }
        .animate-delay-3 { animation-delay: 0.3s; }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="nav">
                <a href="#" class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <span>SchoolLend</span>
                </a>
                
                <ul class="nav-links">
                    <li><a href="#features">Fitur</a></li>
                    <li><a href="#how-it-works">Cara Kerja</a></li>
                    <li><a href="#gallery">Galeri</a></li>
                </ul>
                
                <div class="auth-buttons">
                    <a href="#" class="btn btn-outline">
                        <i class="fas fa-sign-in-alt"></i>
                        Masuk
                    </a>
                </div>
                
                <button class="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </button>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Revolusi Digital Peminjaman Barang Sekolah</h1>
                    <p>Platform terdepan yang mengubah cara sekolah mengelola inventaris dan peminjaman. Mudah, cepat, dan efisien untuk semua kalangan.</p>
                    
                    <div class="hero-buttons">
                        <a href="#" class="btn btn-primary btn-hero">
                            <i class="fas fa-play"></i>
                            Mulai Sekarang
                        </a>
                        <a href="#" class="btn btn-outline btn-hero">
                            <i class="fas fa-video"></i>
                            Lihat Demo
                        </a>
                    </div>
                    
                    <div class="hero-stats">
                        <div class="stat">
                            <span class="stat-number">2,500+</span>
                            <span class="stat-label">Siswa Aktif</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number">150+</span>
                            <span class="stat-label">Sekolah Partner</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number">98%</span>
                            <span class="stat-label">Tingkat Kepuasan</span>
                        </div>
                    </div>
                </div>
                
                <div class="hero-image">
                    <img src="/api/placeholder/600/400" alt="Siswa sedang menggunakan aplikasi SchoolLend di perpustakaan sekolah" class="hero-main-image">
                    
                    <div class="floating-card card-1">
                        <div class="card-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="card-text">Buku Dipinjam</div>
                    </div>
                    
                    <div class="floating-card card-2">
                        <div class="card-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="card-text">Berhasil Dikembalikan</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Fitur Unggulan</span>
                <h2 class="section-title">Mengapa Memilih SchoolLend?</h2>
                <p class="section-subtitle">Solusi lengkap untuk modernisasi sistem peminjaman di sekolah Anda dengan teknologi terdepan</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon icon-blue">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="feature-title">Dashboard Siswa Intuitif</h3>
                    <p class="feature-description">Interface yang user-friendly memungkinkan siswa meminjam buku dan alat tulis dengan mudah. Riwayat peminjaman tersimpan rapi dalam satu tempat.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon icon-green">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3 class="feature-title">Panel Admin Guru</h3>
                    <p class="feature-description">Kelola seluruh inventaris sekolah dengan mudah. Monitor semua peminjaman, terima notifikasi otomatis, dan generate laporan komprehensif.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon icon-orange">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h3 class="feature-title">Notifikasi Real-time</h3>
                    <p class="feature-description">Sistem notifikasi otomatis untuk pengingat batas waktu pengembalian, status peminjaman, dan update inventaris terbaru.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon icon-red">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3 class="feature-title">Analytics & Reporting</h3>
                    <p class="feature-description">Dapatkan insight mendalam tentang tren peminjaman, popularitas item, dan efisiensi operasional sekolah dengan visualisasi data.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Cara Kerja</span>
                <h2 class="section-title">Tiga Langkah Mudah</h2>
                <p class="section-subtitle">Proses yang simpel dan efisien untuk memulai digitalisasi sistem peminjaman di sekolah Anda</p>
            </div>
            
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-image">
                        <img src="/api/placeholder/300/200" alt="Registrasi akun sekolah">
                    </div>
                    <div class="step-number">1</div>
                    <h3 class="step-title">Daftar & Setup</h3>
                    <p class="step-description">Daftarkan sekolah Anda dan lakukan setup awal inventaris. Tim support kami siap membantu proses onboarding.</p>
                </div>
                
                <div class="step-card">
                    <div class="step-image">
                        <img src="/api/placeholder/300/200" alt="Input data inventaris sekolah">
                    </div>
                    <div class="step-number">2</div>
                    <h3 class="step-title">Input Inventaris</h3>
                    <p class="step-description">Masukkan data buku, alat tulis, dan peralatan sekolah. Generate QR code otomatis untuk setiap item dengan mudah.</p>
                </div>
                
                <div class="step-card">
                    <div class="step-image">
                        <img src="/api/placeholder/300/200" alt="Siswa menggunakan aplikasi untuk meminjam">
                    </div>
                    <div class="step-number">3</div>
                    <h3 class="step-title">Mulai Peminjaman</h3>
                    <p class="step-description">Siswa dapat langsung meminjam dengan scan QR code. Monitoring real-time dan laporan otomatis tersedia di dashboard.</p>
                </div>
            </div>
        </div>
    </section>


    <!-- School Gallery Section -->
    <section class="school-gallery" id="gallery">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Galeri Sekolah</span>
                <h2 class="section-title">Sekolah-Sekolah Partner Kami</h2>
                <p class="section-subtitle">Bergabunglah dengan komunitas sekolah modern yang telah mempercayai SchoolLend</p>
            </div>
            
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="/api/placeholder/300/300" alt="SMA Negeri 1 Jakarta">
                    <div class="gallery-overlay">
                        <div>
                            <h4>SMA Negeri 1 Jakarta</h4>
                            <p>2,500+ Siswa Aktif</p>
                        </div>
                    </div>
                </div>
                
                <div class="gallery-item">
                    <img src="/api/placeholder/300/300" alt="SMP Negeri 5 Bandung">
                    <div class="gallery-overlay">
                        <div>
                            <h4>SMP Negeri 5 Bandung</h4>
                            <p>1,800+ Siswa Aktif</p>
                        </div>
                    </div>
                </div>

                <div class="gallery-item">
                    <img src="/api/placeholder/300/300" alt="SMP Negeri 5 Bandung">
                    <div class="gallery-overlay">
                        <div>
                            <h4>SMP Negeri 5 Bandung</h4>
                            <p>1,800+ Siswa Aktif</p>
                        </div>
                    </div>
                </div>
                
                
                <div class="gallery-item">
                    <img src="/api/placeholder/300/300" alt="SMA Katolik Santo Yusup">
                    <div class="gallery-overlay">
                        <div>
                            <h4>SMA Katolik Santo Yusup</h4>
                            <p>1,300+ Siswa Aktif</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2>Siap Memulai Transformasi Digital?</h2>
                <p>Bergabunglah dengan ribuan sekolah yang telah merasakan kemudahan dan efisiensi SchoolLend. Mulai gratis hari ini!</p>
                
                <div class="cta-buttons">
                    <a href="#" class="btn btn-white btn-hero">
                        <i class="fas fa-rocket"></i>
                        Mulai Gratis Sekarang
                    </a>
                    <a href="#" class="btn btn-outline-white btn-hero">
                        <i class="fas fa-phone"></i>
                        Hubungi Sales
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <h3>SchoolLend</h3>
                    <p>Platform peminjaman barang sekolah modern yang memudahkan pengelolaan inventaris dan meningkatkan efisiensi operasional sekolah.</p>
                    <div class="social-links">
                        <a href="#" class="social-link">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>Produk</h4>
                    <ul>
                        <li><a href="#">Fitur Utama</a></li>
                        <li><a href="#">Sistem QR Code</a></li>
                        <li><a href="#">Analytics</a></li>
                        <li><a href="#">Mobile App</a></li>
                        <li><a href="#">API Integration</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Perusahaan</h4>
                    <ul>
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">Tim</a></li>
                        <li><a href="#">Karir</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Press Kit</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Dukungan</h4>
                    <ul>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Dokumentasi</a></li>
                        <li><a href="#">Hubungi Kami</a></li>
                        <li><a href="#">Status Sistem</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 SchoolLend. Semua hak cipta dilindungi. Dibuat dengan ❤️ untuk pendidikan Indonesia.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.header');
            if (window.scrollY > 100) {
                header.style.cssText += 'background: rgba(255, 255, 255, 0.98); box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);';
            } else {
                header.style.cssText += 'background: rgba(255, 255, 255, 0.95); box-shadow: none;';
            }
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.querySelectorAll('.feature-card, .step-card, .testimonial-card').forEach(el => {
            observer.observe(el);
        });

        // Mobile menu toggle (basic implementation)
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const navLinks = document.querySelector('.nav-links');
        
        mobileMenuBtn.addEventListener('click', function() {
            navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
        });

        // Gallery lightbox effect (basic implementation)
        document.querySelectorAll('.gallery-item').forEach(item => {
            item.addEventListener('click', function() {
                // Here you could implement a lightbox/modal functionality
                console.log('Gallery item clicked:', this);
            });
        });

        // Stats counter animation
        function animateStats() {
            const stats = document.querySelectorAll('.stat-number');
            stats.forEach(stat => {
                const target = parseInt(stat.textContent.replace(/[^\d]/g, ''));
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    stat.textContent = Math.floor(current).toLocaleString() + (stat.textContent.includes('%') ? '%' : '+');
                }, 40);
            });
        }

        // Trigger stats animation when hero section is visible
        const heroObserver = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateStats();
                    heroObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        heroObserver.observe(document.querySelector('.hero-stats'));

        // Form submission handlers (if forms are added later)
        function handleFormSubmit(formId, callback) {
            const form = document.getElementById(formId);
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    callback(new FormData(form));
                });
            }
        }

        // Utility function for showing notifications
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.textContent = message;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#10b981' : '#ef4444'};
                color: white;
                padding: 16px 24px;
                border-radius: 12px;
                z-index: 10000;
                transform: translateX(100%);
                transition: transform 0.3s ease;
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);
            
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Any initialization code goes here
            console.log('SchoolLend website loaded successfully!');
        });
    </script>
</body>
</html>