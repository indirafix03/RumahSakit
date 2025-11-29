<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Vite (Tailwind + App Styles) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            /* Primary Colors */
            --primary-blue: #094fa4;
            --secondary-blue: #0065c1;
            --accent-blue: #009ee5;
            --light-blue: #52bcec;
            
            /* Status Colors */
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            
            /* Neutral Colors */
            --dark-text: #2c3e50;
            --light-text: #7f8c8d;
            --light-bg: #f8f9fa;
            --white: #ffffff;
            
            /* Background Gradients */
            --gradient-primary: linear-gradient(135deg, var(--secondary-blue) 0%, var(--accent-blue) 100%);
            --gradient-danger: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            --gradient-success: linear-gradient(135deg, var(--success) 0%, #059669 100%);
            --gradient-warning: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
            --gradient-info: linear-gradient(135deg, var(--info) 0%, #2563eb 100%);
            
            /* Glassmorphism */
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.2);
            --glass-shadow: 0 8px 32px rgba(0, 101, 193, 0.12);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f9ff 0%, #e6f3ff 100%);
            color: var(--dark-text);
            min-height: 100vh;
            letter-spacing: 0.3px;
        }

        h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
            font-family: 'Oswald', sans-serif;
            letter-spacing: 0.8px;
            font-weight: 600;
        }

        /* Content Cards */
        .content-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: var(--glass-shadow);
            border: 1px solid var(--glass-border);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .content-card:hover {
            box-shadow: 0 12px 40px rgba(0, 101, 193, 0.15);
        }

        .card-header-custom {
            background: linear-gradient(135deg, rgba(0, 101, 193, 0.05) 0%, rgba(0, 158, 229, 0.05) 100%);
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(0, 101, 193, 0.1);
        }

        .card-title-custom {
            font-size: 1.4rem;
            font-weight: 600;
            margin: 0;
            color: var(--primary-blue);
            display: flex;
            align-items: center;
        }

        .card-badge {
            background: var(--gradient-primary);
            color: var(--white);
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            margin-left: 0.8rem;
        }

        .card-body-custom {
            padding: 2rem;
        }

        /* Button Styles */
        .btn-primary-custom {
            background: var(--gradient-primary);
            color: var(--white);
            border: none;
            padding: 0.8rem 1.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 101, 193, 0.2);
            font-family: 'Inter', sans-serif;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 101, 193, 0.3);
            color: var(--white);
        }

        .btn-success-custom {
            background: var(--gradient-success);
            color: var(--white);
            border: none;
            padding: 0.5rem 1rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
        }

        .btn-success-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            color: var(--white);
        }

        .btn-outline-custom {
            background: transparent;
            border: 1px solid rgba(0, 101, 193, 0.2);
            color: var(--primary-blue);
            padding: 0.8rem 1.5rem;
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.3s ease;
            width: 100%;
            text-align: left;
        }

        .btn-outline-custom:hover {
            background: rgba(0, 101, 193, 0.05);
            border-color: var(--secondary-blue);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 101, 193, 0.1);
            color: var(--primary-blue);
        }

        /* Stats Cards */
        .stats-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid var(--glass-border);
            box-shadow: var(--glass-shadow);
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 101, 193, 0.18);
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: white;
        }

        .stats-number {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-family: 'Oswald', sans-serif;
        }

        .stats-label {
            color: var(--light-text);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 500;
        }

        /* Page Header */
        .page-header {
            background: var(--gradient-primary);
            color: var(--white);
            padding: 2.5rem 0;
            margin: -1rem -1rem 2.5rem -1rem;
            position: relative;
            overflow: hidden;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 20px rgba(9, 79, 164, 0.15);
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
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            position: relative;
        }

        .page-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 300;
            position: relative;
        }

        /* List Group Styles */
        .list-group-item-custom {
            border: none;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.2rem 1.5rem;
            transition: all 0.3s ease;
        }

        .list-group-item-custom:hover {
            background: rgba(0, 101, 193, 0.03);
            border-left: 3px solid var(--secondary-blue);
        }

        .list-group-item-custom:last-child {
            border-bottom: none;
        }

        /* Schedule Cards */
        .schedule-card {
            background: var(--glass-bg);
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(245, 158, 11, 0.2);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.1);
            height: 100%;
        }

        .schedule-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.15);
        }

        .schedule-time {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--warning);
            margin-bottom: 0.5rem;
            font-family: 'Oswald', sans-serif;
        }

        .schedule-day {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-text);
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        /* Empty State */
        .empty-state {
            padding: 3rem 2rem;
            text-align: center;
        }

        .empty-state-icon {
            font-size: 3.5rem;
            color: var(--light-text);
            margin-bottom: 1.5rem;
            opacity: 0.5;
        }

        .empty-state-title {
            color: var(--light-text);
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .empty-state-text {
            color: var(--light-text);
            max-width: 400px;
            margin: 0 auto 1.5rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }
            
            .card-title-custom {
                font-size: 1.2rem;
            }
            
            .card-body-custom {
                padding: 1.5rem;
            }
            
            .stats-number {
                font-size: 1.8rem;
            }
            
            .schedule-time {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Content -->
        <main class="p-4">
            @yield('content')
        </main>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>