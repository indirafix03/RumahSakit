<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Dokter - {{ config('app.name') }}</title>
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
                    <a href="{{ route('poli.public') }}" class="text-gray-600 hover:text-blue-600">Poli</a>
                    <a href="{{ route('dokter.public') }}" class="text-blue-600 font-semibold">Dokter</a>
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
            <h1 class="text-4xl font-bold mb-4">Tim Dokter Spesialis Kami</h1>
            <p class="text-xl opacity-90">Dokter profesional yang siap memberikan pelayanan terbaik</p>
        </div>
    </div>

    <!-- Doctors List -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- Filter by Poli -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Cari Dokter Berdasarkan Spesialisasi</h2>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('dokter.public') }}" 
                   class="px-4 py-2 rounded-full border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white transition-colors">
                    Semua Dokter
                </a>
                @foreach(\App\Models\Poli::all() as $poli)
                <a href="{{ route('dokter.public') }}?poli={{ $poli->id }}" 
                   class="px-4 py-2 rounded-full border border-gray-300 text-gray-600 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-colors">
                    {{ $poli->nama_poli }}
                </a>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($dokters as $dokter)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-md text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">dr. {{ $dokter->name }}</h3>
                            <p class="text-blue-600 font-semibold">{{ $dokter->poli->nama_poli ?? 'Umum' }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-graduation-cap mr-2"></i>
                            <span>{{ $dokter->spesialisasi ?? 'Dokter Spesialis' }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-star text-yellow-400 mr-2"></i>
                            <span>Pengalaman {{ $dokter->pengalaman ?? '5+' }} tahun</span>
                        </div>
                    </div>

                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                        {{ $dokter->deskripsi ?? 'Dokter profesional yang berpengalaman dalam bidangnya.' }}
                    </p>

                    <div class="bg-blue-50 rounded-lg p-3 mb-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-blue-700 font-semibold">Jadwal Praktik</span>
                            <span class="text-blue-600">{{ $dokter->schedules_count }} hari/minggu</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-6 py-4 border-t">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">
                            Informasi detail
                        </span>
                        <a href="{{ route('dokter.detail', $dokter->id) }}" 
                           class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                            Lihat Profil <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($dokters->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-user-md text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Dokter Tersedia</h3>
            <p class="text-gray-500">Informasi dokter akan segera diupdate</p>
        </div>
        @endif
    </div>

    <!-- Call to Action -->
    <div class="bg-gray-100 py-12">
        <div class="max-w-4xl mx-auto text-center px-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Ingin Berkonsultasi dengan Dokter?</h2>
            <p class="text-gray-600 mb-6">Daftar sebagai pasien untuk dapat membuat janji temu</p>
            @auth
                <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700">
                    Buat Janji Temu
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