<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Dokter - {{ config('app.name') }}</title>
    
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
            background-color: var(--light-bg);
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
        
        .nav-link.active {
            color: var(--secondary-color);
            font-weight: 600;
        }
        
        .btn-login {
            background-color: var(--secondary-color);
            color: var(--white);
            border: none;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            border-radius: 6px;
        }
        
        .btn-login:hover {
            background-color: var(--primary-text);
            color: var(--white);
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--card-color) 100%);
            color: var(--white);
            padding: 4rem 0;
            text-align: center;
        }
        
        .hero-title {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            max-width: 800px;
            margin: 0 auto;
            opacity: 0.9;
            font-weight: 300;
            letter-spacing: 0.5px;
        }
        
        .section-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
            color: var(--primary-text);
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        
        .filter-section {
            background-color: var(--white);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }
        
        .filter-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--primary-text);
            letter-spacing: 0.5px;
        }
        
        .filter-badge {
            display: inline-block;
            padding: 0.5rem 1.2rem;
            margin: 0.3rem;
            border: 1px solid var(--secondary-color);
            color: var(--secondary-color);
            text-decoration: none;
            border-radius: 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .filter-badge:hover, .filter-badge.active {
            background-color: var(--secondary-color);
            color: var(--white);
        }
        
        .doctor-card {
            background: var(--white);
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
            border-radius: 8px;
            border-left: 4px solid var(--secondary-color);
        }
        
        .doctor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .doctor-avatar {
            width: 70px;
            height: 70px;
            background-color: var(--background-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
        }
        
        .doctor-icon {
            font-size: 1.8rem;
            color: var(--white);
        }
        
        .doctor-name {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
            color: var(--primary-text);
            letter-spacing: 0.5px;
        }
        
        .doctor-specialty {
            font-size: 1rem;
            color: var(--secondary-color);
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        
        .doctor-info {
            color: var(--light-text);
            font-size: 0.9rem;
            font-weight: 300;
            margin-bottom: 0.5rem;
        }
        
        .doctor-description {
            color: var(--light-text);
            font-weight: 300;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }
        
        .schedule-badge {
            background-color: var(--background-color);
            color: var(--primary-text);
            padding: 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            display: block;
        }

        .schedule-badge .stat-number {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--primary-text);
            line-height: 1;
        }

        .schedule-badge .stat-label {
            font-size: 0.85rem;
            color: rgba(0,0,0,0.65);
        }
        
        .btn-view-profile {
            background-color: var(--secondary-color);
            color: var(--white);
            border: none;
            padding: 0.5rem 1.2rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
            border-radius: 6px;
        }
        
        .btn-view-profile:hover {
            background-color: var(--primary-text);
            color: var(--white);
        }
        
        .cta-section {
            background-color: var(--background-color);
            padding: 4rem 0;
            text-align: center;
        }
        
        .cta-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--primary-text);
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        
        .cta-subtitle {
            font-size: 1.2rem;
            color: var(--primary-text);
            margin-bottom: 2rem;
            font-weight: 300;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .btn-register {
            background-color: var(--secondary-color);
            color: var(--white);
            border: none;
            padding: 0.8rem 2rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            font-size: 1.1rem;
            border-radius: 8px;
            text-transform: uppercase;
        }
        
        .btn-register:hover {
            background-color: var(--primary-text);
            color: var(--white);
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
        
        .empty-state {
            text-align: center;
            padding: 4rem 0;
        }
        
        .empty-icon {
            font-size: 4rem;
            color: var(--background-color);
            margin-bottom: 1.5rem;
        }
        
        .empty-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--primary-text);
            margin-bottom: 1rem;
        }
        
        .empty-text {
            color: var(--light-text);
            font-size: 1.1rem;
            font-weight: 300;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
            
            .cta-title {
                font-size: 1.8rem;
            }
            
            .doctor-avatar {
                width: 60px;
                height: 60px;
                margin-right: 0.8rem;
            }
            
            .doctor-icon {
                font-size: 1.5rem;
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
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('poli.public') }}">Poli</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('dokter.public') }}">Dokter</a>
                    </li>
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a class="nav-link btn-login" href="{{ url('/dashboard') }}">Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link btn-login" href="{{ route('login') }}">Masuk</a>
                            </li>
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">Tim Dokter Spesialis Kami</h1>
            <p class="hero-subtitle">
                Dokter profesional yang siap memberikan pelayanan terbaik
            </p>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="py-4">
        <div class="container">
            <div class="filter-section">
                <h2 class="filter-title">Cari Dokter Berdasarkan Spesialisasi</h2>
                <div class="filter-tags">
                    <a href="{{ route('dokter.public') }}" 
                       class="filter-badge {{ !request('poli') ? 'active' : '' }}">
                        Semua Dokter
                    </a>
                    @foreach(\App\Models\Poli::all() as $poli)
                    <a href="{{ route('dokter.public') }}?poli={{ $poli->id }}" 
                       class="filter-badge {{ request('poli') == $poli->id ? 'active' : '' }}">
                        {{ $poli->nama_poli }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Doctors List Section -->
    <section class="py-4">
        <div class="container">
            <h2 class="section-title">Dokter Tersedia</h2>
            
            @if($dokters->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-user-md"></i>
                </div>
                <h3 class="empty-title">Belum Ada Dokter Tersedia</h3>
                <p class="empty-text">Informasi dokter akan segera diupdate</p>
            </div>
            @else
            <div class="row g-4">
                @foreach($dokters as $dokter)
                <div class="col-md-6 col-lg-4">
                    <div class="card doctor-card h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start mb-3">
                                <div class="doctor-avatar">
                                    <i class="fas fa-user-md doctor-icon"></i>
                                </div>
                                <div>
                                    <h3 class="doctor-name">dr. {{ $dokter->name }}</h3>
                                    <p class="doctor-specialty">{{ $dokter->poli->nama_poli ?? 'Umum' }}</p>
                                </div>
                            </div>
                            
                            <div class="doctor-info mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-graduation-cap me-2"></i>
                                    <span>{{ $dokter->spesialisasi ?? 'Dokter Spesialis' }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-star text-warning me-2"></i>
                                    <span>Pengalaman {{ $dokter->pengalaman ?? '5+' }} tahun</span>
                                </div>
                            </div>

                            <p class="doctor-description">
                                {{ $dokter->deskripsi ?? 'Dokter profesional yang berpengalaman dalam bidangnya.' }}
                            </p>

                            <div class="schedule-badge mb-3">
                                <div class="row align-items-center">
                                    <div class="col-7">
                                        <div class="fw-bold">Jadwal Praktik</div>
                                        <div class="stat-label">Hari aktif</div>
                                    </div>
                                    <div class="col-5 text-end">
                                        <div class="stat-number">{{ $dokter->schedules_count }}</div>
                                        <div class="stat-label">hari/minggu</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">Informasi detail</span>
                                <a href="{{ route('dokter.detail', $dokter->id) }}" 
                                   class="btn btn-view-profile">
                                    Lihat Profil <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title">Ingin Berkonsultasi dengan Dokter?</h2>
            <p class="cta-subtitle">
                Daftar sebagai pasien untuk dapat membuat janji temu
            </p>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-register">
                    Buat Janji Temu
                </a>
            @else
                <a href="{{ route('register') }}" class="btn btn-register">
                    Daftar Sekarang
                </a>
            @endauth
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'RS. INDIRA') }}. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>