<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Dr. {{ $dokter->name }} - {{ config('app.name') }}</title>
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

    <!-- Doctor Profile -->
    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-blue-600 text-white p-8">
                <div class="flex items-center space-x-6">
                    <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-md text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">dr. {{ $dokter->name }}</h1>
                        <p class="text-xl opacity-90">{{ $dokter->poli->nama_poli ?? 'Dokter Umum' }}</p>
                        <p class="opacity-80 mt-2">{{ $dokter->spesialisasi ?? 'Dokter Spesialis' }}</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <!-- About -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Tentang Dokter</h2>
                    <p class="text-gray-600 leading-relaxed">
                        {{ $dokter->deskripsi ?? 'Dokter profesional yang berpengalaman dalam memberikan pelayanan kesehatan terbaik untuk pasien.' }}
                    </p>
                </div>

                <!-- Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h3 class="font-semibold text-blue-800 mb-2">Spesialisasi</h3>
                        <p class="text-gray-700">{{ $dokter->poli->nama_poli ?? 'Umum' }}</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4">
                        <h3 class="font-semibold text-green-800 mb-2">Pengalaman</h3>
                        <p class="text-gray-700">{{ $dokter->pengalaman ?? '5+' }} tahun</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4">
                        <h3 class="font-semibold text-purple-800 mb-2">Pendidikan</h3>
                        <p class="text-gray-700">{{ $dokter->pendidikan ?? 'Dokter Spesialis' }}</p>
                    </div>
                    <div class="bg-orange-50 rounded-lg p-4">
                        <h3 class="font-semibold text-orange-800 mb-2">No. STR</h3>
                        <p class="text-gray-700">{{ $dokter->no_str ?? 'Terdaftar' }}</p>
                    </div>
                </div>

                <!-- Schedule -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Jadwal Praktik</h2>
                    @if($dokter->schedules->count() > 0)
                    <div class="space-y-3">
                        @foreach($dokter->schedules as $schedule)
                        <div class="flex justify-between items-center bg-gray-50 rounded-lg p-4">
                            <div>
                                <span class="font-semibold text-gray-800 capitalize">
                                    {{ ucfirst($schedule->hari) }}
                                </span>
                                <p class="text-sm text-gray-600">{{ $schedule->jam_mulai }} - {{ \Carbon\Carbon::parse($schedule->jam_mulai)->addMinutes($schedule->durasi)->format('H:i') }}</p>
                            </div>
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                                Tersedia
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-yellow-800 text-center">Jadwal praktik belum tersedia</p>
                    </div>
                    @endif
                </div>

                <!-- Note -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-blue-800 mb-1">Informasi</h4>
                            <p class="text-blue-700 text-sm">
                                Untuk membuat janji temu dengan dr. {{ $dokter->name }}, silakan daftar atau login terlebih dahulu.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-center space-x-4 mt-8">
            <a href="{{ route('dokter.public') }}" class="bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            @auth
                <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                    Buat Janji Temu
                </a>
            @else
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                    Daftar untuk Janji Temu
                </a>
            @endauth
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>