<x-platform::layouts.admin title="Ringkasan Sistem">

    @php
        // Mengambil data langsung dari database (tanpa 'use' statement)
        $totalClients = \App\Models\Client::count();
                // --- TAMBAHKAN KODE INI UNTUK GRAFIK REAL ---
        $chartLabels = [];
        $chartData = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            // Label bulan (contoh: Jan, Feb, Mar)
            $chartLabels[] = $month->translatedFormat('M');

            // Hitung jumlah pesantren yang dibuat pada bulan tersebut
            $chartData[] = \App\Models\Client::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }
        // --------------------------------------------
        $activeClients = \App\Models\Client::where('status', 'active')->count();
        $inactiveClients = \App\Models\Client::where('status', 'inactive')->count();
        $pendingClients = \App\Models\Client::where('status', 'pending')->count();
    @endphp

    <!-- Sapaan -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Assalamu'alaikum, {{ auth()->user()->name }}</h2>
        <p class="text-gray-500 mt-1">Semua sistem cloud berjalan optimal. Berikut adalah ringkasan platform Anda hari ini.</p>
    </div>

    <!-- Kartu Statistik (Sekarang Dinamis) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Pesantren -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Total Pesantren</p>
            <div class="flex items-end justify-between mt-2">
                <h3 class="text-4xl font-extrabold text-gray-900">{{ $totalClients }}</h3>
                <span class="text-sm font-bold text-green-600 bg-green-50 px-2 py-1 rounded-md">Data Asli</span>
            </div>
        </div>

        <!-- Pesantren Aktif -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Pesantren Aktif</p>
            <div class="flex items-end justify-between mt-2">
                <h3 class="text-4xl font-extrabold text-gray-900">{{ $activeClients }}</h3>
                <span class="text-sm font-bold text-green-600 bg-green-50 px-2 py-1 rounded-md">Data Asli</span>
            </div>
        </div>

        <!-- Menunggu Aktivasi -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Menunggu Aktivasi</p>
            <div class="flex items-end justify-between mt-2">
                <h3 class="text-4xl font-extrabold text-gray-900">{{ $pendingClients }}</h3>
                <span class="text-sm font-bold text-gray-600 bg-gray-100 px-2 py-1 rounded-md">Data Asli</span>
            </div>
        </div>

        <!-- Nonaktif -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Nonaktif</p>
            <div class="flex items-end justify-between mt-2">
                <h3 class="text-4xl font-extrabold text-gray-900">{{ $inactiveClients }}</h3>
                <span class="text-sm font-bold text-red-600 bg-red-50 px-2 py-1 rounded-md">Data Asli</span>
            </div>
        </div>
    </div>

    <!-- Bagian Bawah (Tetap Sama) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6 min-h-[300px] flex flex-col">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Aktivitas Bulanan & Pertumbuhan</h3>
<div class="flex-1 w-full relative min-h-[250px]">
    <canvas id="growthChart"></canvas>
</div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Aktivitas Log Platform</h3>
            <ul class="space-y-4">
                <li class="flex gap-3">
                    <div class="w-2 h-2 mt-2 rounded-full bg-green-500 shrink-0"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Aktivasi Pesantren Al-Hikmah</p>
                        <p class="text-xs text-gray-500">Oleh Super Admin • 10 menit yang lalu</p>
                    </div>
                </li>
                <li class="flex gap-3">
                    <div class="w-2 h-2 mt-2 rounded-full bg-blue-500 shrink-0"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Konfigurasi Modul Diperbarui</p>
                        <p class="text-xs text-gray-500">Pondok Modern Darussalam • 1 jam yang lalu</p>
                    </div>
                </li>
                <li class="flex gap-3">
                    <div class="w-2 h-2 mt-2 rounded-full bg-red-500 shrink-0"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Gagal Login Terdeteksi</p>
                        <p class="text-xs text-gray-500">IP 192.168.1.14 • 4 jam yang lalu</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>

        <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('growthChart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Pendaftaran Pesantren',
                        data: {!! json_encode($chartData) !!},
                        borderColor: '#065f46',
                        backgroundColor: 'rgba(6, 95, 70, 0.1)',
                        borderWidth: 2,
                        pointBackgroundColor: '#065f46',
                        pointRadius: 4,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                        x: { grid: { display: false } }
                    }
                }
            });
        });
    </script>
</x-platform::layouts.admin>
