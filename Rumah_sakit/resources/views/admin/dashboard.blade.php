@extends('layouts.app')

@section('content')
<div class="min-h-screen">
    <main>
        <!-- Page Header -->
        <div class="page-header">
            <div class="container position-relative">
                <h1 class="page-title">Admin Dashboard</h1>
                <p class="page-subtitle mb-0">Overview sistem dan monitoring real-time</p>
            </div>
        </div>

        <div class="container">
            <!-- Elegant Stats Cards -->
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
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
        }
        
        body {
            font-family: 'Oswald', sans-serif;
            color: var(--primary-text);
            line-height: 1.6;
            font-weight: 400;
            background: linear-gradient(135deg, #f0f9ff 0%, #e6f3ff 100%);
            min-height: 100vh;
        }
        
        .admin-navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            background: linear-gradient(135deg, var(--primary-text) 0%, var(--secondary-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 0.5px;
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--primary-text);
            margin: 0 0.5rem;
            letter-spacing: 0.5px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--card-color) 100%);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }
        
        .nav-link.active {
            color: var(--secondary-color);
            font-weight: 600;
        }
        
        .btn-logout {
            background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
            color: var(--white);
            border: none;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }
        
        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.3);
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--card-color) 100%);
            color: var(--white);
            padding: 3rem 0;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><polygon points="0,0 1000,50 1000,100 0,100"/></svg>');
            background-size: cover;
        }
        
        .page-title {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            position: relative;
        }
        
        /* Elegant Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        
        .stats-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 
                0 8px 32px rgba(0, 101, 193, 0.1),
                0 2px 8px rgba(0, 101, 193, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--card-color) 100%);
        }
        
        .stats-card:hover {
            transform: translateY(-8px);
            box-shadow: 
                0 16px 40px rgba(0, 101, 193, 0.15),
                0 4px 12px rgba(0, 101, 193, 0.1);
        }
        
        .stats-card.users {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(240, 249, 255, 0.9) 100%);
        }
        
        .stats-card.appointments {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 250, 240, 0.9) 100%);
        }
        
        .stats-card.medicines {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(240, 255, 244, 0.9) 100%);
        }
        
        .stats-card.doctors {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(240, 245, 255, 0.9) 100%);
        }
        
        .stats-icon {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            position: relative;
            z-index: 1;
        }
        
        .stats-icon.users {
            background: linear-gradient(135deg, var(--info-color) 0%, #60a5fa 100%);
            color: white;
        }
        
        .stats-icon.appointments {
            background: linear-gradient(135deg, var(--warning-color) 0%, #fbbf24 100%);
            color: white;
        }
        
        .stats-icon.medicines {
            background: linear-gradient(135deg, var(--success-color) 0%, #34d399 100%);
            color: white;
        }
        
        .stats-icon.doctors {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--card-color) 100%);
            color: white;
        }
        
        .stats-content {
            position: relative;
            z-index: 1;
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--primary-text) 0%, var(--secondary-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .stats-label {
            font-size: 1rem;
            color: var(--light-text);
            font-weight: 500;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
        }
        
        .stats-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .stats-detail-item {
            text-align: center;
            flex: 1;
        }
        
        .detail-number {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .detail-label {
            font-size: 0.75rem;
            color: var(--light-text);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stats-trend {
            display: flex;
            align-items: center;
            font-size: 0.85rem;
            font-weight: 500;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
        }
        
        .stats-trend.up {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }
        
        /* Section Cards */
        .section-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 
                0 8px 32px rgba(0, 101, 193, 0.1),
                0 2px 8px rgba(0, 101, 193, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .section-card:hover {
            box-shadow: 
                0 12px 40px rgba(0, 101, 193, 0.15),
                0 4px 12px rgba(0, 101, 193, 0.1);
        }
        
        .section-header {
            background: linear-gradient(135deg, rgba(0, 101, 193, 0.05) 0%, rgba(0, 158, 229, 0.05) 100%);
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
            color: var(--primary-text);
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
        }
        
        .section-badge {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--card-color) 100%);
            color: var(--white);
            padding: 0.4rem 1rem;
            border-radius: 15px;
            font-weight: 600;
            font-size: 0.8rem;
            margin-left: 0.8rem;
        }
        
        .section-body {
            padding: 2rem;
        }
        
        /* Doctor Items */
        .doctor-item {
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(0, 101, 193, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }
        
        .doctor-item:hover {
            background: rgba(255, 255, 255, 0.9);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 101, 193, 0.1);
        }
        
        .doctor-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 0.25rem;
        }
        
        .doctor-specialty {
            font-size: 0.9rem;
            color: var(--light-text);
            margin-bottom: 0.5rem;
        }
        
        .doctor-email {
            font-size: 0.85rem;
            color: var(--light-text);
        }
        
        .appointment-badge {
            font-size: 0.8rem;
            padding: 0.25rem 0.6rem;
            border-radius: 15px;
        }
        
        /* Role Cards */
        .role-card {
            border-radius: 15px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }
        
        .role-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .role-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
        }
        
        .role-count {
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .role-percentage {
            font-size: 0.8rem;
            color: var(--light-text);
        }
        
        /* Poli Stats */
        .poli-stats-card {
            border: 1px solid rgba(0, 101, 193, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
            background: rgba(255, 255, 255, 0.7);
        }
        
        .poli-stats-card:hover {
            border-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 101, 193, 0.1);
            background: rgba(255, 255, 255, 0.9);
        }
        
        .poli-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-text);
            margin-bottom: 1rem;
        }
        
        .poli-count {
            font-size: 1.8rem;
            font-weight: 700;
        }
        
        /* Table Styling */
        .table-custom {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(5px);
        }
        
        .table-custom thead th {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--card-color) 100%);
            color: var(--white);
            font-weight: 600;
            letter-spacing: 0.5px;
            border: none;
            padding: 1rem;
        }
        
        .table-custom tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-color: rgba(0, 0, 0, 0.05);
        }
        
        .table-custom tbody tr:hover {
            background: rgba(0, 101, 193, 0.03);
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }
        
        .empty-icon {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 1.5rem;
            opacity: 0.5;
        }
        
        .empty-text {
            color: var(--light-text);
            font-size: 1.1rem;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .page-title {
                font-size: 2.2rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-number {
                font-size: 2rem;
            }
            
            .section-title {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen">
        <main>
            <div class="container">
                <!-- Elegant Stats Cards -->
                <div class="stats-grid">
                    <!-- Total Users Card -->
                    <div class="stats-card users">
                        <div class="stats-icon users">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stats-content">
                            <div class="stats-number">{{ $userStats['total_users'] ?? 0 }}</div>
                            <div class="stats-label">Total Pengguna</div>
                            <div class="stats-details">
                                <div class="stats-detail-item">
                                    <div class="detail-number text-info">{{ $userStats['total_admin'] ?? 0 }}</div>
                                    <div class="detail-label">Admin</div>
                                </div>
                                <div class="stats-detail-item">
                                    <div class="detail-number text-primary">{{ $userStats['total_dokter'] ?? 0 }}</div>
                                    <div class="detail-label">Dokter</div>
                                </div>
                                <div class="stats-detail-item">
                                    <div class="detail-number text-success">{{ $userStats['total_pasien'] ?? 0 }}</div>
                                    <div class="detail-label">Pasien</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Appointments Card -->
                    <div class="stats-card appointments">
                        <div class="stats-icon appointments">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stats-content">
                            <div class="stats-number">{{ $appointmentStats['pending_appointments'] ?? 0 }}</div>
                            <div class="stats-label">Appointments Pending</div>
                            <div class="stats-details">
                                <div class="stats-detail-item">
                                    <div class="detail-number">{{ $appointmentStats['pending_appointments'] ?? 0 }}</div>
                                    <div class="detail-label">Menunggu</div>
                                </div>
                                <div class="stats-detail-item">
                                    <div class="detail-number">{{ ($appointmentStats['total_appointments'] ?? 0) - ($appointmentStats['pending_appointments'] ?? 0) }}</div>
                                    <div class="detail-label">Disetujui</div>
                                </div>
                                <div class="stats-detail-item">
                                    <div class="detail-number">{{ $appointmentStats['total_appointments'] ?? 0 }}</div>
                                    <div class="detail-label">Total</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Medicine Card -->
                    <div class="stats-card medicines">
                        <div class="stats-icon medicines">
                            <i class="fas fa-pills"></i>
                        </div>
                        <div class="stats-content">
                            <div class="stats-number">{{ $medicineStats['total_obat'] ?? 0 }}</div>
                            <div class="stats-label">Total Obat</div>
                            <div class="stats-details">
                                <div class="stats-detail-item">
                                    <div class="detail-number text-success">{{ $medicineStats['obat_tersedia'] ?? 0 }}</div>
                                    <div class="detail-label">Tersedia</div>
                                </div>
                                <div class="stats-detail-item">
                                    <div class="detail-number text-warning">{{ $medicineStats['obat_habis'] ?? 0 }}</div>
                                    <div class="detail-label">Habis</div>
                                </div>
                                <div class="stats-detail-item">
                                    <span class="stats-trend up">
                                        <i class="fas fa-arrow-up me-1"></i>
                                        12%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dokter Bertugas Card -->
                    <div class="stats-card doctors">
                        <div class="stats-icon doctors">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div class="stats-content">
                            <div class="stats-number">{{ $dokterBertugasHariIni->count() ?? 0 }}</div>
                            <div class="stats-label">Dokter Bertugas</div>
                            <div class="stats-details">
                                <div class="stats-detail-item">
                                    <div class="detail-number">{{ now()->format('d M') }}</div>
                                    <div class="detail-label">Hari Ini</div>
                                </div>
                                <div class="stats-detail-item">
                                    <div class="detail-number">{{ $totalDokter ?? '0' }}</div>
                                    <div class="detail-label">Total</div>
                                </div>
                                <div class="stats-detail-item">
                                    <span class="stats-trend up">
                                        <i class="fas fa-calendar-check me-1"></i>
                                        Active
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dokter Bertugas Hari Ini & Pending Appointments -->
                <div class="row g-4 mb-5">
                    <!-- Dokter yang Sedang Bertugas Hari Ini -->
                    <div class="col-lg-6">
                        <div class="section-card">
                            <div class="section-header">
                                <h2 class="section-title">
                                    <i class="fas fa-user-md text-info me-2"></i>
                                    Dokter Bertugas Hari Ini
                                    <span class="section-badge">{{ $dokterBertugasHariIni->count() ?? 0 }} dokter</span>
                                </h2>
                            </div>
                            <div class="section-body">
                                @if($dokterBertugasHariIni && $dokterBertugasHariIni->count() > 0)
                                    <div class="row g-3">
                                        @foreach($dokterBertugasHariIni as $dokter)
                                            <div class="col-12">
                                                <div class="doctor-item">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div class="flex-grow-1">
                                                            <h4 class="doctor-name">Dr. {{ $dokter->name }}</h4>
                                                            <p class="doctor-specialty">
                                                                <i class="fas fa-hospital me-1"></i>
                                                                {{ $dokter->poli->nama_poli ?? 'Tidak ada poli' }}
                                                            </p>
                                                            <p class="doctor-email">
                                                                <i class="fas fa-envelope me-1"></i>
                                                                {{ $dokter->email }}
                                                            </p>
                                                            
                                                            @if(($dokter->total_appointments_today ?? 0) > 0)
                                                                <div class="mt-2">
                                                                    <span class="badge bg-success appointment-badge">
                                                                        <i class="fas fa-calendar-check me-1"></i>
                                                                        {{ $dokter->total_appointments_today }} appointment hari ini
                                                                    </span>
                                                                </div>
                                                            @else
                                                                <span class="badge bg-secondary appointment-badge">
                                                                    <i class="fas fa-info-circle me-1"></i>
                                                                    Tidak ada appointment hari ini
                                                                </span>
                                                            @endif
                                                        </div>
                                                        
                                                        @if(($dokter->total_appointments_today ?? 0) > 0)
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-check-circle me-1"></i>
                                                                Bertugas
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">
                                                                <i class="fas fa-clock me-1"></i>
                                                                Tersedia
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="fas fa-user-md"></i>
                                        </div>
                                        <p class="empty-text">Tidak ada dokter yang bertugas hari ini</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Pending Appointments untuk Approval -->
                    <div class="col-lg-6">
                        <div class="section-card">
                            <div class="section-header">
                                <h2 class="section-title">
                                    <i class="fas fa-clock text-warning me-2"></i>
                                    Pending Appointments Menunggu Approval
                                    @if($pendingAppointments && $pendingAppointments->count() > 0)
                                        <span class="section-badge">{{ $pendingAppointments->count() }} appointments</span>
                                    @endif
                                </h2>
                            </div>
                            <div class="section-body">
                                @if($pendingAppointments && $pendingAppointments->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-custom table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Pasien</th>
                                                    <th>Dokter</th>
                                                    <th>Poli</th>
                                                    <th>Tanggal</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($pendingAppointments as $appointment)
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold">{{ $appointment->pasien->name ?? 'N/A' }}</div>
                                                        <small class="text-muted">{{ $appointment->pasien->email ?? 'N/A' }}</small>
                                                    </td>
                                                    <td class="fw-bold">Dr. {{ $appointment->dokter->name ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge bg-primary">
                                                            {{ $appointment->dokter->poli->nama_poli ?? 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $appointment->tanggal_booking->format('d M Y') ?? 'N/A' }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.appointments.show', $appointment) }}" class="btn btn-sm btn-primary">
                                                            Review
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <p class="empty-text">Tidak ada appointment yang menunggu approval</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistik Poli -->
                <div class="row mb-5">
                    <div class="col-12">
                        <div class="section-card">
                            <div class="section-header">
                                <h2 class="section-title">
                                    <i class="fas fa-hospital text-primary me-2"></i>
                                    Statistik Poli
                                    @if($poliStats && $poliStats->count() > 0)
                                        <span class="section-badge">{{ $poliStats->count() }} poli</span>
                                    @endif
                                </h2>
                            </div>
                            <div class="section-body">
                                @if($poliStats && $poliStats->count() > 0)
                                    <div class="row g-4">
                                        @foreach($poliStats as $poli)
                                            <div class="col-md-6 col-lg-3">
                                                <div class="poli-stats-card">
                                                    <h4 class="poli-name">{{ $poli->nama_poli ?? 'Unknown Poli' }}</h4>
                                                    <div class="row text-center">
                                                        <div class="col-6">
                                                            <div class="poli-count text-primary">{{ $poli->doctors_count ?? 0 }}</div>
                                                            <small class="text-muted">Dokter</small>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="poli-count text-success">{{ $poli->appointments_today_count ?? 0 }}</div>
                                                            <small class="text-muted">Appointments</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="fas fa-hospital"></i>
                                        </div>
                                        <p class="empty-text">Tidak ada data poli yang tersedia</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    // Dashboard scripts if needed
</script>
@endpush