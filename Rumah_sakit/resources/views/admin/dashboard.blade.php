<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="/admin/dashboard" class="text-xl font-bold">Admin</a>
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

    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-bold">Total Users</h3>
                <p class="text-2xl">{{ $stats['total_pasien'] + $stats['total_dokter'] }}</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-bold">Pending Appointments</h3>
                <p class="text-2xl">{{ $stats['pending_appointments'] }}</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-bold">Total Medicine</h3>
                <p class="text-2xl">{{ $stats['total_obat'] }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h3 class="font-bold mb-4">Pending Appointments</h3>
            @if($pendingAppointments->count() > 0)
                @foreach($pendingAppointments as $appointment)
                    <div class="border p-4 mb-2 rounded">
                        <p><strong>Patient:</strong> {{ $appointment->pasien->name }}</p>
                        <p><strong>Dokter:</strong> {{ $appointment->dokter->name }}</p>
                        <p><strong>Keluhan:</strong> {{ $appointment->keluhan_singkat }}</p>
                    </div>
                @endforeach
            @else
                <p>No pending appointments</p>
            @endif
        </div>
    </div>
</body>
</html>