<!DOCTYPE html>
<html>
<head>
    <title>Dokter Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="/dokter/dashboard" class="text-xl font-bold">Dokter</a>
                    <a href="/dokter/schedules" class="hover:text-blue-600">Jadwal</a>
                    <a href="/dokter/appointments" class="hover:text-blue-600">Janji Temu</a>
                    <a href="/dokter/medical-records" class="hover:text-blue-600">Rekam Medis</a>
                </div>
                <div class="flex items-center">
                    <span class="mr-4">{{ Auth::user()->name ?? 'Dokter' }}</span>
                    <form method="POST" action="/logout">
                        @csrf
                        <button class="bg-red-500 text-white px-4 py-2 rounded">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">Dokter Dashboard</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-bold">Pending Appointments</h3>
                <p class="text-2xl">{{ $stats['pending_appointments'] }}</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-bold">Today's Appointments</h3>
                <p class="text-2xl">{{ $stats['today_appointments'] }}</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-bold">Total Patients</h3>
                <p class="text-2xl">{{ $stats['total_patients'] }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h3 class="font-bold mb-4">Today's Appointments</h3>
            @if($todayAppointments->count() > 0)
                @foreach($todayAppointments as $appointment)
                    <div class="border p-4 mb-2 rounded">
                        <p><strong>Patient:</strong> {{ $appointment->patient->name }}</p>
                        <p><strong>Time:</strong> {{ $appointment->schedule->jam_mulai }}</p>
                        <p><strong>Keluhan:</strong> {{ $appointment->keluhan_singkat }}</p>
                    </div>
                @endforeach
            @else
                <p>No appointments today</p>
            @endif
        </div>
    </div>
</body>
</html>