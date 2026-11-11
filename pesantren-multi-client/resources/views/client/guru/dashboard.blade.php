<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navbar Guru -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center gap-2">
                        <div class="w-8 h-8 bg-emerald-600 text-white flex items-center justify-center font-bold rounded">S</div>
                        <span class="text-xl font-bold text-gray-800">SiSantri (Guru)</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-gray-600">Ustadz Ahmad Fauzi</span>
                    <a href="#" class="text-sm font-semibold text-red-600 hover:text-red-800">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Jadwal Mengajar Anda</h1>
            <p class="text-sm text-gray-500 mt-1">Berikut adalah daftar kelas yang menjadi tanggung jawab Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($schedules as $schedule)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2.5 py-1 rounded-full">{{ $schedule->day }}</span>
                        <h3 class="text-lg font-bold text-gray-800 mt-2">{{ $schedule->subjects->name ?? 'Mata Pelajaran' }}</h3>
                        <p class="text-sm text-gray-500">{{ $schedule->classes->name ?? 'Kelas' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</p>
                        <p class="text-xs text-gray-500">s.d. {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</p>
                    </div>
                </div>
                
                <a href="{{ url('/client/demo/admin/attendance/' . $schedule->id) }}" class="block w-full text-center bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                    Isi Absensi Kelas
                </a>
            </div>
            @empty
            <div class="col-span-full bg-white p-12 text-center rounded-xl border border-gray-100">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <h3 class="text-lg font-medium text-gray-900 mb-1">Belum Ada Jadwal</h3>
                <p class="text-gray-500">Anda belum memiliki jadwal mengajar saat ini.</p>
            </div>
            @endforelse
        </div>
    </main>
</body>
</html>