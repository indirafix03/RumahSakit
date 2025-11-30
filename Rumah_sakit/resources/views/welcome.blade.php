<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --primary-text: #094fa4;
            --secondary-color: #0065c1;
            --card-color: #009ee5;
            --background-color: #52bcec;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
            --light-text: #7f8c8d;
            --white: #ffffff;
        }
        
        body {
            font-family: 'Oswald', sans-serif;
            color: var(--primary-text);
            line-height: 1.6;
            font-weight: 400;
        }
        
        .navbar {
            background-color: var(--white);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--primary-text);
            letter-spacing: 0.5px;
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--primary-text);
            margin: 0 0.5rem;
            letter-spacing: 0.5px;
            font-size: 1.1rem;
        }
        
        .btn-login {
            background-color: var(--secondary-color);
            color: var(--white);
            border: none;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        
        .btn-login:hover {
            background-color: var(--primary-text);
            color: var(--white);
        }
        
        .btn-register {
            border: 1px solid var(--secondary-color);
            color: var(--secondary-color);
            background: transparent;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        
        .btn-register:hover {
            background-color: var(--secondary-color);
            color: var(--white);
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--card-color) 100%);
            color: var(--white);
            padding: 5rem 0;
            text-align: center;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        
        .hero-subtitle {
            font-size: 1.4rem;
            max-width: 800px;
            margin: 0 auto 2rem;
            opacity: 0.9;
            font-weight: 300;
            letter-spacing: 0.5px;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 2.5rem;
            text-align: center;
            color: var(--primary-text);
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        
        .service-card {
            background: var(--card-color);
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
            border-radius: 0;
            border-left: 4px solid var(--secondary-color);
            color: var(--white);
        }
        
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .card-icon {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            color: var(--white);
        }
        
        .card-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 1.2rem;
            color: var(--white);
            letter-spacing: 0.5px;
        }
        
        .card-description {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
            font-weight: 300;
        }
        
        .features-section {
            background-color: var(--background-color);
            padding: 5rem 0;
        }
        
        .feature-card {
            background: var(--white);
            padding: 2.5rem 2rem;
            text-align: center;
            border-radius: 0;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            height: 100%;
            border-top: 3px solid var(--secondary-color);
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            color: var(--secondary-color);
        }
        
        .feature-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.2rem;
            color: var(--primary-text);
            letter-spacing: 0.5px;
        }
        
        .feature-description {
            color: var(--primary-text);
            font-weight: 300;
            font-size: 1.05rem;
        }
        
        .about-section {
            padding: 5rem 0;
            background: var(--white);
        }
        
        .about-title {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 2rem;
            color: var(--primary-text);
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        
        .about-text {
            font-size: 1.2rem;
            color: var(--primary-text);
            max-width: 800px;
            margin: 0 auto;
            font-weight: 300;
            line-height: 1.8;
        }
        
        .footer {
            background-color: var(--primary-text);
            color: var(--white);
            padding: 3rem 0 2rem;
        }
        
        .footer p {
            margin-bottom: 0.5rem;
            font-weight: 300;
            letter-spacing: 0.5px;
        }
        
        .stats-section {
            background: var(--white);
            padding: 4rem 0;
        }
        
        .stat-card {
            text-align: center;
            padding: 2rem 1.5rem;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 0.8rem;
            letter-spacing: 1px;
        }
        
        .stat-label {
            font-size: 1.1rem;
            color: var(--primary-text);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 500;
        }
        
        .divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 2rem 0;
        }
        
        .doctor-card {
            background: var(--white);
            border: none;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            border-radius: 0;
            border-left: 3px solid var(--secondary-color);
            padding: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .doctor-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .doctor-name {
            font-weight: 600;
            color: var(--primary-text);
            font-size: 1.4rem;
            letter-spacing: 0.5px;
        }
        
        .doctor-specialty {
            color: var(--secondary-color);
            font-size: 1.1rem;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        
        .bg-custom-light {
            background-color: var(--background-color);
        }
        
        .text-primary-custom {
            color: var(--primary-text);
        }
        
        .text-secondary-custom {
            color: var(--secondary-color);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .about-title {
                font-size: 2.2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
        <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'MedicalSystem') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/dashboard') }}">Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item me-2">
                                <a class="btn btn-login" href="{{ route('login') }}">Masuk</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="btn btn-register" href="{{ route('register') }}">Daftar</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">Selamat Datang di RS. INDIRA</h1>
            <p class="hero-subtitle">
                Memberikan pelayanan kesehatan terbaik dengan tim dokter profesional dan fasilitas modern
            </p>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-card">
                        <div class="stat-number">40k+</div>
                        <div class="stat-label">Pasien</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-card">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Dokter</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-card">
                        <div class="stat-number">15+</div>
                        <div class="stat-label">Spesialisasi</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-card">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Pelayanan</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Cards Section -->
    <section class="py-5 bg-custom-light">
        <div class="container">
            <h2 class="section-title text-white">Layanan Kami</h2>
            <div class="row g-4">
                <!-- Poli Card -->
                <div class="col-md-6">
                    <a href="{{ route('poli.public') }}" class="text-decoration-none">
                        <div class="card service-card h-100">
                            <div class="card-body p-4">
                                <div class="card-icon">
                                    <i class="fas fa-hospital"></i>
                                </div>
                                <h3 class="card-title">Lihat Daftar Poli</h3>
                                <p class="card-description">
                                    Temukan berbagai poli spesialis yang tersedia di rumah sakit kami. 
                                    Dari poli umum hingga spesialis, kami siap melayani kebutuhan kesehatan Anda.
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
                
                <!-- Dokter Card -->
                <div class="col-md-6">
                    <a href="{{ route('dokter.public') }}" class="text-decoration-none">
                        <div class="card service-card h-100">
                            <div class="card-body p-4">
                                <div class="card-icon">
                                    <i class="fas fa-user-md"></i>
                                </div>
                                <h3 class="card-title">Cari Dokter</h3>
                                <p class="card-description">
                                    Kenali tim dokter profesional kami. Lihat profil, spesialisasi, 
                                    dan jadwal praktik dokter untuk memudahkan konsultasi kesehatan Anda.
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h2 class="section-title text-white">Fitur Unggulan</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h3 class="feature-title">Janji Temu Online</h3>
                        <p class="feature-description">
                            Buat janji temu dengan dokter pilihan Anda kapan saja dan di mana saja. 
                            Sistem yang mudah digunakan untuk pasien dan dokter.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-file-medical"></i>
                        </div>
                        <h3 class="feature-title">Rekam Medis Digital</h3>
                        <p class="feature-description">
                            Simpan dan kelola rekam medis pasien secara digital. 
                            Akses riwayat kesehatan dengan cepat dan aman.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-pills"></i>
                        </div>
                        <h3 class="feature-title">Manajemen Resep</h3>
                        <p class="feature-description">
                            Kelola resep obat dengan sistem yang terintegrasi. 
                            Pantau stok obat dan buat resep dengan mudah.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <h3 class="feature-title">Dokter Berpengalaman</h3>
                        <p class="feature-description">
                            Tim dokter profesional yang siap memberikan pelayanan terbaik. 
                            Konsultasi dengan ahli di bidangnya.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="feature-title">Keamanan Data</h3>
                        <p class="feature-description">
                            Data pasien dan rekam medis dilindungi dengan sistem keamanan terbaik. 
                            Privasi Anda adalah prioritas kami.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3 class="feature-title">Akses Mudah</h3>
                        <p class="feature-description">
                            Sistem yang responsif dan mudah diakses dari berbagai perangkat. 
                            Pengalaman pengguna yang optimal.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container text-center">
            <h2 class="about-title">Tentang RS. INDIRA</h2>
            <p class="about-text">
                RS. INDIRA berkomitmen untuk menyediakan pelayanan kesehatan yang modern, 
                efisien, dan mudah diakses oleh seluruh masyarakat. Dengan teknologi terkini 
                dan tim medis yang profesional, kami siap memberikan pelayanan terbaik 
                untuk kesehatan Anda dan keluarga.
            </p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <p>&copy; {{ date('Y') }} RS. INDIRA. All rights reserved.</p>
            <p>Email: info@rsindira.com | Phone: (021) 1234-5678</p>
            <p>Alamat: Jl. Kesehatan No. 123, Jakarta Pusat</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>