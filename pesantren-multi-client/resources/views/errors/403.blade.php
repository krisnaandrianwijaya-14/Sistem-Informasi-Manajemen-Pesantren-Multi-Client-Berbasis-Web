
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | SiSantri</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen font-sans p-4">

    <div class="w-full max-w-lg p-8 bg-white rounded-2xl shadow-xl border border-gray-100 text-center">
        <!-- Ikon Gembok -->
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-red-50 text-red-500 mb-6">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>

        <h1 class="text-6xl font-extrabold text-gray-900 mb-2">403</h1>
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Akses Ditolak</h2>

        <p class="text-gray-500 mb-8">
            Maaf, Anda tidak memiliki hak akses (otorisasi) untuk membuka halaman ini. Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.
        </p>

        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-bold rounded-lg text-white bg-[#065f46] hover:bg-[#047857] transition shadow-md">
            &larr; Kembali ke Dashboard
        </a>
    </div>

</body>
</html>
