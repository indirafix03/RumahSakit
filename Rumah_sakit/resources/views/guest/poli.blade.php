<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Poli - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="{{ url('/') }}" class="text-2xl font-bold text-blue-600">
                    {{ config('app.name') }}
                </a>
                <div class="flex space-x-4">
                    <a href="{{ url('/') }}" class="text-gray-600 hover:text-blue-600">Beranda</a>
                    <a href="{{ route('poli.public') }}" class="text-blue-600 font-semibold">Poli</a>
                    <a href="{{ route('dokter.public') }}" class="text-gray-600 hover:text-blue-600">Dokter</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Masuk</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-blue-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-4">Daftar Poli Klinik</h1>
            <p class="text-xl opacity-90">Temukan poli yang sesuai dengan kebutuhan kesehatan Anda</p>
        </div>
    </div>

    <!-- Poli List -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($polis as $poli)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-800">{{ $poli->nama_poli }}</h3>
                        <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $poli->dokters_count }} Dokter
                        </span>
                    </div>
                    
                    <p class="text-gray-600 mb-4 line-clamp-3">
                        {{ $poli->deskripsi ?? 'Layanan medis spesialis ' . $poli->nama_poli }}
                    </p>
                    
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <span>
                            <i class="fas fa-user-md mr-1"></i>
                            Spesialis
                        </span>
                        <span>
                            <i class="fas fa-clock mr-1"></i>
                            Buka Senin - Jumat
                        </span>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-6 py-4 border-t">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">
                            Layanan tersedia
                        </span>
                        <a href="{{ route('dokter.public') }}?poli={{ $poli->id }}" 
                           class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                            Lihat Dokter <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($polis->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-hospital text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Poli Tersedia</h3>
            <p class="text-gray-500">Informasi poli akan segera diupdate</p>
        </div>
        @endif
    </div>

    <!-- Call to Action -->
    <div class="bg-gray-100 py-12">
        <div class="max-w-4xl mx-auto text-center px-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Butuh Konsultasi dengan Dokter?</h2>
            <p class="text-gray-600 mb-6">Daftar sekarang untuk dapat membuat janji temu dengan dokter spesialis</p>
            @auth
                <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700">
                    Ke Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700">
                    Daftar Sekarang
                </a>
            @endauth
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>