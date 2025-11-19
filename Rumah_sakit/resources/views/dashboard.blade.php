<!DOCTYPE html>
<html>
<head>
    <title>Klinik Reservasi</title>
    <!-- Gunakan Bootstrap untuk konsistensi -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .welcome-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .welcome-card {
            background: white;
            border-radius: 15px;
            padding: 3rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <div class="welcome-card">
            <h1 class="display-4 fw-bold text-primary mb-4">RESERVASI RUMAH SAKIT</h1>
            <p class="lead mb-4">Sistem Reservasi Klinik Digital</p>
            
            @auth
                <div class="alert alert-info">
                    Anda sudah login sebagai {{ auth()->user()->name }}
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                    Lanjut ke Dashboard
                </a>
            @else
                <div class="d-grid gap-3">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">
                        Register
                    </a>
                </div>
            @endauth
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>