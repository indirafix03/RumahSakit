<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="/admin/dashboard" class="text-xl font-bold">Admin Dashboard</a>
                    <a href="/admin/users" class="hover:text-blue-600">Users</a>
                    <a href="/admin/polis" class="hover:text-blue-600">Poli</a>
                    <a href="/admin/medicines" class="hover:text-blue-600">Obat</a>
                    <a href="/admin/appointments" class="hover:text-blue-600">Janji Temu</a>
                </div>
                <div class="flex items-center">
                    <span class="mr-4">{{ Auth::user()->name ?? 'Admin' }}</span>
                    <form method="POST" action="/logout">
                        @csrf
                        <button class="bg-red-500 text-white px-4 py-2 rounded">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
        
        <!-- Statistik Utama -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <!-- Total Users -->
            <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-users text-blue-500 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-semibold text-gray-500">Total Users</h3>
                        <p class="text-2xl font-bold">{{ $userStats['total_users'] }}</p>
                    </div>
                </div>
                <div class="mt-2 text-xs text-gray-500">
                    Admin: {{ $userStats['total_admin'] }} | 
                    Dokter: {{ $userStats['total_dokter'] }} | 
                    Pasien: {{ $userStats['total_pasien'] }}
                </div>
            </div>

            <!-- Pending Appointments -->
            <div class="bg-white p-6 rounded-lg shadow border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-clock text-yellow-500 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-semibold text-gray-500">Pending Appointments</h3>
                        <p class="text-2xl font-bold">{{ $appointmentStats['pending_appointments'] }}</p>
                    </div>
                </div>
                <div class="mt-2 text-xs text-gray-500">
                    Total: {{ $appointmentStats['total_appointments'] }} appointments
                </div>
            </div>

            <!-- Total Medicine -->
            <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-pills text-green-500 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-semibold text-gray-500">Total Medicine</h3>
                        <p class="text-2xl font-bold">{{ $medicineStats['total_obat'] }}</p>
                    </div>
                </div>
                <div class="mt-2 text-xs text-gray-500">
                    Tersedia: {{ $medicineStats['obat_tersedia'] }} | Habis: {{ $medicineStats['obat_habis'] }}
                </div>
            </div>

            <!-- Dokter Bertugas -->
            <div class="bg-white p-6 rounded-lg shadow border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-user-md text-purple-500 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-semibold text-gray-500">Dokter Bertugas</h3>
                        <p class="text-2xl font-bold">{{ $dokterBertugasHariIni->count() }}</p>
                    </div>
                </div>
                <div class="mt-2 text-xs text-gray-500">
                    Hari ini: {{ now()->format('d M Y') }}
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Dokter yang Sedang Bertugas Hari Ini -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-bold text-lg mb-4 flex items-center">
                    <i class="fas fa-user-md text-purple-500 mr-2"></i>
                    Dokter Bertugas Hari Ini
                    <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full ml-2">
                        {{ $dokterBertugasHariIni->count() }} dokter
                    </span>
                </h3>
                
                @if($dokterBertugasHariIni->count() > 0)
                    <div class="space-y-4">
                        @foreach($dokterBertugasHariIni as $dokter)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-blue-600">Dr. {{ $dokter->name }}</h4>
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-hospital mr-1"></i>
                                            {{ $dokter->poli->nama_poli ?? 'Tidak ada poli' }}
                                        </p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            <i class="fas fa-envelope mr-1"></i>
                                            {{ $dokter->email }}
                                        </p>
                                        
                                        @if($dokter->total_appointments_today > 0)
                                            <div class="mt-2">
                                                <p class="text-xs text-green-600 font-semibold">
                                                    <i class="fas fa-calendar-check mr-1"></i>
                                                    {{ $dokter->total_appointments_today }} appointment hari ini
                                                </p>
                                                @foreach($dokter->appointments_today->take(2) as $appointment)
                                                    <p class="text-xs text-gray-500 ml-4">
                                                        • {{ $appointment->pasien->name }} 
                                                        @if($appointment->schedule)
                                                            ({{ \Carbon\Carbon::parse($appointment->schedule->jam_mulai)->format('H:i') }})
                                                        @endif
                                                    </p>
                                                @endforeach
                                                @if($dokter->total_appointments_today > 2)
                                                    <p class="text-xs text-gray-400 ml-4">
                                                        • dan {{ $dokter->total_appointments_today - 2 }} lainnya...
                                                    </p>
                                                @endif
                                            </div>
                                        @else
                                            <p class="text-xs text-gray-400 mt-2">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Tidak ada appointment hari ini
                                            </p>
                                        @endif
                                    </div>
                                    
                                    @if($dokter->total_appointments_today > 0)
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Bertugas
                                        </span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">
                                            <i class="fas fa-clock mr-1"></i>
                                            Tersedia
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-user-md text-4xl mb-3 text-gray-300"></i>
                        <p>Tidak ada dokter yang bertugas hari ini</p>
                    </div>
                @endif
            </div>

            <!-- Distribusi Pengguna Berdasarkan Peran -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-bold text-lg mb-4 flex items-center">
                    <i class="fas fa-chart-pie text-blue-500 mr-2"></i>
                    Distribusi Pengguna Berdasarkan Peran
                </h3>
                
                <div class="space-y-4">
                    <!-- Admin -->
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="bg-blue-500 p-2 rounded-full mr-3">
                                <i class="fas fa-cog text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-semibold">Admin</p>
                                <p class="text-sm text-gray-600">System Administrator</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-bold">
                                {{ $userStats['total_admin'] }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">
                                @if($userStats['total_users'] > 0)
                                    {{ number_format(($userStats['total_admin'] / $userStats['total_users']) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Dokter -->
                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="bg-purple-500 p-2 rounded-full mr-3">
                                <i class="fas fa-user-md text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-semibold">Dokter</p>
                                <p class="text-sm text-gray-600">Medical Practitioners</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full font-bold">
                                {{ $userStats['total_dokter'] }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">
                                @if($userStats['total_users'] > 0)
                                    {{ number_format(($userStats['total_dokter'] / $userStats['total_users']) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Pasien -->
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="bg-green-500 p-2 rounded-full mr-3">
                                <i class="fas fa-user-injured text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-semibold">Pasien</p>
                                <p class="text-sm text-gray-600">Patients</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full font-bold">
                                {{ $userStats['total_pasien'] }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">
                                @if($userStats['total_users'] > 0)
                                    {{ number_format(($userStats['total_pasien'] / $userStats['total_users']) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-2xl font-bold text-blue-600">{{ $userStats['total_admin'] }}</p>
                            <p class="text-xs text-gray-600">Admin</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-purple-600">{{ $userStats['total_dokter'] }}</p>
                            <p class="text-xs text-gray-600">Dokter</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-green-600">{{ $userStats['total_pasien'] }}</p>
                            <p class="text-xs text-gray-600">Pasien</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Poli -->
        @if($poliStats && $poliStats->count() > 0)
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h3 class="font-bold text-lg mb-4 flex items-center">
                <i class="fas fa-hospital text-blue-500 mr-2"></i>
                Statistik Poli
                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full ml-2">
                    {{ $poliStats->count() }} poli
                </span>
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-{{ min($poliStats->count(), 4) }} gap-4">
                @foreach($poliStats as $poli)
                <div class="border border-gray-200 rounded-lg p-4 text-center hover:shadow-md transition-shadow">
                    <h4 class="font-semibold text-blue-600 mb-2">{{ $poli->nama_poli ?? 'Unknown Poli' }}</h4>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="bg-blue-50 p-3 rounded-lg">
                            <p class="font-bold text-2xl text-blue-600">{{ $poli->doctors_count ?? 0 }}</p>
                            <p class="text-xs text-gray-600 mt-1">Dokter</p>
                        </div>
                        <div class="bg-green-50 p-3 rounded-lg">
                            <p class="font-bold text-2xl text-green-600">{{ $poli->appointments_today_count ?? 0 }}</p>
                            <p class="text-xs text-gray-600 mt-1">Appointments Hari Ini</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <!-- Tampilkan pesan jika tidak ada data poli -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h3 class="font-bold text-lg mb-4 flex items-center">
                <i class="fas fa-hospital text-blue-500 mr-2"></i>
                Statistik Poli
            </h3>
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-hospital text-4xl mb-3 text-gray-300"></i>
                <p>Tidak ada data poli yang tersedia</p>
            </div>
        </div>
        @endif

        <!-- Pending Appointments untuk Approval -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-bold text-lg mb-4 flex items-center">
                <i class="fas fa-clock text-yellow-500 mr-2"></i>
                Pending Appointments Menunggu Approval
                <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full ml-2">
                    {{ $pendingAppointments->count() }} appointments
                </span>
            </h3>
            
            @if($pendingAppointments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dokter</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poli</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keluhan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pendingAppointments as $appointment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $appointment->pasien->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $appointment->pasien->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Dr. {{ $appointment->dokter->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $appointment->dokter->poli->nama_poli ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">{{ $appointment->keluhan_singkat }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $appointment->tanggal_booking->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.appointments.show', $appointment) }}" class="text-blue-600 hover:text-blue-900">Review</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-check-circle text-4xl mb-3 text-gray-300"></i>
                    <p>Tidak ada appointment yang menunggu approval</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>