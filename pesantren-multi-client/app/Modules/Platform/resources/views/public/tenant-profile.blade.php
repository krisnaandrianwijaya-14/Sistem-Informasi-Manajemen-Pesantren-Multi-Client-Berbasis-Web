<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $client->name }} - Website Resmi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased">

    <!-- Navbar Profil -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Identitas Pesantren -->
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                        {{ substr($client->name, 0, 1) }}
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ $client->name }}</h1>
                        <p class="text-sm text-gray-500">Portal Resmi Pesantren</p>
                    </div>
                </div>

                <!-- Tombol ke Login -->
                <div>
                    <a href="{{ route('login') }}" class="px-5 py-2 rounded-lg border-2 border-green-600 text-green-700 font-semibold hover:bg-green-50 transition">
                        Login Sivitas
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section Pesantren -->
    <header class="bg-green-700 text-white">
        <div class="max-w-7xl mx-auto py-20 px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-6 tracking-tight">
                Selamat Datang di <br> {{ $client->name }}
            </h1>
            <p class="text-lg md:text-xl text-green-100 max-w-3xl mx-auto mb-8">
                "Mencetak Generasi Qur'ani, Berakhlak Mulia, dan Berwawasan Global."
            </p>
        </div>
    </header>

    <!-- Tentang Pesantren -->
    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Tentang Kami</h2>
            <div class="text-lg text-gray-600 leading-relaxed text-left bg-gray-50 p-8 rounded-2xl border border-gray-100">
                <p>
                    <!-- Fallback jika kolom description kosong -->
                    {{ $client->description ?? 'Pesantren ini berkomitmen untuk menyelenggarakan pendidikan yang memadukan kurikulum nasional dengan kajian kitab kuning, membentuk karakter santri yang siap menghadapi tantangan zaman.' }}
                </p>
            </div>
        </div>
    </section>

    <!-- Informasi Kontak & Alamat -->
    <section class="py-16 bg-gray-50 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Alamat -->
                <div class="bg-white p-6 rounded-xl shadow-sm text-center">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Alamat</h3>
                    <p class="text-gray-600">
                        {{ $client->address ?? 'Jl. Pendidikan Pesantren No. 1, Indonesia' }}
                    </p>
                </div>
                <!-- Kontak -->
                <div class="bg-white p-6 rounded-xl shadow-sm text-center">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Kontak</h3>
                    <p class="text-gray-600">
                        {{ $client->phone ?? '0812-3456-7890' }}
                    </p>
                </div>
                <!-- Status -->
                <div class="bg-white p-6 rounded-xl shadow-sm text-center">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Status Sistem</h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        Aktif
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-500">
            &copy; {{ date('Y') }} {{ $client->name }}. Powered by <strong class="text-green-600">SiSantri</strong>.
        </div>
    </footer>

</body>
</html>
