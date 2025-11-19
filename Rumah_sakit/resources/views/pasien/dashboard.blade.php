<!DOCTYPE html>
<html>
<head>
    <title>Pasien Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="/pasien/dashboard" class="text-xl font-bold">👤 Pasien</a>
                    <a href="/pasien/appointments" class="hover:text-blue-600">Janji Temu</a>
                    <a href="/pasien/medical-records" class="hover:text-blue-600">Rekam Medis</a>
                </div>
                <div class="flex items-center">
                    <span class="mr-4">{{ Auth::user()->name ?? 'Pasien' }}</span>
                    <form method="POST" action="/logout">
                        @csrf
                        <button class="bg-red-500 text-white px-4 py-2 rounded">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">Pasien Dashboard</h1>
        
        @if($lastAppointment)
        <div class="bg-white p-6 rounded shadow mb-6">
            <h3 class="font-bold mb-4">Last Appointment</h3>
            <p><strong>Dokter:</strong> {{ $lastAppointment->doctor->name }}</p>
            <p><strong>Tanggal:</strong> {{ $lastAppointment->tanggal_booking }}</p>
            <p><strong>Status:</strong> {{ $lastAppointment->status }}</p>
        </div>
        @endif

        <div class="bg-white p-6 rounded shadow">
            <h3 class="font-bold mb-4">Upcoming Appointments</h3>
            @if($approvedAppointments->count() > 0)
                @foreach($approvedAppointments as $appointment)
                    <div class="border p-4 mb-2 rounded">
                        <p><strong>Dokter:</strong> {{ $appointment->doctor->name }}</p>
                        <p><strong>Tanggal:</strong> {{ $appointment->tanggal_booking }}</p>
                    </div>
                @endforeach
            @else
                <p>No upcoming appointments</p>
            @endif
        </div>
    </div>
</body>
</html>