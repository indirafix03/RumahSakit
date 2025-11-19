<!DOCTYPE html>
<html>
<head>
    <title>My Appointments</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="/pasien/dashboard" class="text-xl font-bold">👤 Pasien</a>
                    <a href="/pasien/appointments" class="hover:text-blue-600 font-bold">Janji Temu</a>
                    <a href="/pasien/medical-records" class="hover:text-blue-600">Rekam Medis</a>
                </div>
                <div class="flex items-center">
                    <span class="mr-4">{{ Auth::user()->name }}</span>
                    <form method="POST" action="/logout">
                        @csrf
                        <button class="bg-red-500 text-white px-4 py-2 rounded">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">My Appointments</h1>
        <div class="bg-white p-6 rounded shadow">
            <p>Appointment management functionality will be here</p>
        </div>
    </div>
</body>
</html>