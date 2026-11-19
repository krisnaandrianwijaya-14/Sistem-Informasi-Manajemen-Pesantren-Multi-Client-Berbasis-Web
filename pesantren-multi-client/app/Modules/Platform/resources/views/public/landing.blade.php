<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiSantri - Sistem Manajemen Pesantren Modern</title>

    <!-- Pastikan Vite sudah dikonfigurasi dengan Tailwind di project Anda -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">
                        S
                    </div>
                    <span class="text-2xl font-bold text-green-700 tracking-tight">SiSantri.</span>
                </div>

                <!-- Navigasi & Tombol Login -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#fitur" class="text-gray-600 hover:text-green-600 font-medium transition">Fitur</a>
                    <a href="#tentang" class="text-gray-600 hover:text-green-600 font-medium transition">Tentang Kami</a>
                    <a href="{{ route('login') }}" class="px-6 py-2.5 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700 transition shadow-md hover:shadow-lg">
                        Masuk Sistem
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32 pt-20 px-4 sm:px-6 lg:px-8">
                <main class="mt-10 mx-auto max-w-7xl sm:mt-12 md:mt-16 lg:mt-20 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">Kelola Pesantren</span>
                            <span class="block text-green-600 xl:inline">Lebih Modern</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Platform manajemen pesantren multi-client pertama yang terintegrasi. Kelola data santri, akademik, asrama, hingga keuangan dalam satu genggaman. Lengkap dengan website profil pesantren Anda.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="https://wa.me/6281234567890" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 md:py-4 md:text-lg md:px-10 transition">
                                    Konsultasi Gratis
                                </a>
                            </div>
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                                <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 md:py-4 md:text-lg md:px-10 transition">
                                    Login Portal
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <!-- Ornamen Background -->
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-green-50 flex items-center justify-center p-12 hidden lg:flex">
            <div class="text-green-600 text-9xl font-black opacity-20 select-none">
                SiSantri
            </div>
        </div>
    </header>

    <!-- Fitur Section -->
    <section id="fitur" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-extrabold text-gray-900">Fitur Unggulan</h2>
                <p class="mt-4 text-lg text-gray-500">Semua yang dibutuhkan pesantren Anda untuk beralih ke sistem digital dalam satu platform terpadu.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Fitur 1 -->
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Manajemen Data Santri</h3>
                    <p class="text-gray-500 leading-relaxed">Kelola biodata, riwayat, dan status santri secara terpusat. Mendukung multi-angkatan dan wali santri.</p>
                </div>

                <!-- Fitur 2 -->
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Akademik & Asrama</h3>
                    <p class="text-gray-500 leading-relaxed">Pantau jadwal, absensi kelas, hingga perizinan asrama dengan sistem *real-time* yang saling terintegrasi.</p>
                </div>

                <!-- Fitur 3 -->
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Website Profil Pesantren</h3>
                    <p class="text-gray-500 leading-relaxed">Dapatkan sub-domain website profil pesantren secara otomatis untuk publikasi informasi ke masyarakat umum.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-500">
            &copy; {{ date('Y') }} SiSantri Platform. Hak Cipta Dilindungi.
        </div>
    </footer>

</body>
</html>
