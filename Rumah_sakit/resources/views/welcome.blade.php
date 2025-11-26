<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .guest-navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .nav-links {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .btn-login {
            background: #667eea;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 0.375rem;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s ease;
        }
        
        .btn-login:hover {
            background: #5a6fd8;
            color: white;
        }
        
        .btn-register {
            border: 2px solid #667eea;
            color: #667eea;
            padding: 0.5rem 1.5rem;
            border-radius: 0.375rem;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-register:hover {
            background: #667eea;
            color: white;
        }
        
        .header-section {
            padding: 3rem 2rem 2rem 2rem;
            text-align: center;
            color: white;
        }
        
        .header-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .header-subtitle {
            font-size: 1.5rem;
            margin-bottom: 3rem;
            opacity: 0.9;
            font-weight: 300;
        }
        
        .cards-section {
            padding: 2rem 2rem 4rem 2rem;
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
        }
        
        .service-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 1.5rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
            text-align: center;
        }
        
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .card-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            display: block;
        }
        
        .card-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #1f2937;
        }
        
        .card-description {
            color: #6b7280;
            line-height: 1.6;
            font-size: 1.1rem;
        }
        
        .poli-card {
            border-left: 5px solid #667eea;
        }
        
        .dokter-card {
            border-left: 5px solid #10b981;
        }
        
        .footer {
            background: rgba(0, 0, 0, 0.8);
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 2rem;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
            text-decoration: none;
        }

        .hospital-name {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            text-align: center;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="guest-navbar">
        <div class="nav-container">
            <a href="{{ url('/') }}" class="logo">
                {{ config('app.name', 'MedicalSystem') }}
            </a>
            <div class="nav-links">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-login">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-login">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-register">Daftar</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Header Section -->
    <section class="header-section">
        <h1 class="header-title">Selamat Datang di RS. INDIRA</h1>
        <p class="header-subtitle">
            Memberikan pelayanan kesehatan terbaik dengan tim dokter profesional dan fasilitas modern
        </p>
    </section>

    <!-- Service Cards Section -->
    <section class="cards-section">
        <div class="cards-grid">
            <!-- Poli Card -->
            <a href="{{ route('poli.public') }}" class="service-card poli-card">
                <div class="card-icon">🏥</div>
                <h2 class="card-title">Lihat Daftar Poli</h2>
                <p class="card-description">
                    Temukan berbagai poli spesialis yang tersedia di rumah sakit kami. 
                    Dari poli umum hingga spesialis, kami siap melayani kebutuhan kesehatan Anda.
                </p>
            </a>
            
            <!-- Dokter Card -->
            <a href="{{ route('dokter.public') }}" class="service-card dokter-card">
                <div class="card-icon">👨‍⚕️</div>
                <h2 class="card-title">Cari Dokter</h2>
                <p class="card-description">
                    Kenali tim dokter profesional kami. Lihat profil, spesialisasi, 
                    dan jadwal praktik dokter untuk memudahkan konsultasi kesehatan Anda.
                </p>
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">📅</div>
                <h3 class="feature-title">Janji Temu Online</h3>
                <p class="feature-description">
                    Buat janji temu dengan dokter pilihan Anda kapan saja dan di mana saja. 
                    Sistem yang mudah digunakan untuk pasien dan dokter.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">📋</div>
                <h3 class="feature-title">Rekam Medis Digital</h3>
                <p class="feature-description">
                    Simpan dan kelola rekam medis pasien secara digital. 
                    Akses riwayat kesehatan dengan cepat dan aman.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">💊</div>
                <h3 class="feature-title">Manajemen Resep</h3>
                <p class="feature-description">
                    Kelola resep obat dengan sistem yang terintegrasi. 
                    Pantau stok obat dan buat resep dengan mudah.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">👨‍⚕️</div>
                <h3 class="feature-title">Dokter Berpengalaman</h3>
                <p class="feature-description">
                    Tim dokter profesional yang siap memberikan pelayanan terbaik. 
                    Konsultasi dengan ahli di bidangnya.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h3 class="feature-title">Keamanan Data</h3>
                <p class="feature-description">
                    Data pasien dan rekam medis dilindungi dengan sistem keamanan terbaik. 
                    Privasi Anda adalah prioritas kami.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">📱</div>
                <h3 class="feature-title">Akses Mudah</h3>
                <p class="feature-description">
                    Sistem yang responsif dan mudah diakses dari berbagai perangkat. 
                    Pengalaman pengguna yang optimal.
                </p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="hero-section" id="about">
        <h2 class="hero-title">Tentang RS. INDIRA</h2>
        <p class="hero-subtitle">
            RS. INDIRA berkomitmen untuk menyediakan pelayanan kesehatan yang modern, 
            efisien, dan mudah diakses oleh seluruh masyarakat. Dengan teknologi terkini 
            dan tim medis yang profesional, kami siap memberikan pelayanan terbaik 
            untuk kesehatan Anda dan keluarga.
        </p>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <p>&copy; {{ date('Y') }} RS. INDIRA. All rights reserved.</p>
        <p>Email: info@rsindira.com | Phone: (021) 1234-5678</p>
        <p>Alamat: Jl. Kesehatan No. 123, Jakarta Pusat</p>
    </footer>
</body>
</html>