<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | SiSantri</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen font-sans p-4">

    <div class="w-full max-w-lg p-8 bg-white rounded-2xl shadow-xl border border-gray-100 text-center">
        <!-- Ikon Kompas/Peta -->
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-amber-50 text-amber-500 mb-6">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>

        <h1 class="text-6xl font-extrabold text-gray-900 mb-2">404</h1>
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Halaman Tidak Ditemukan</h2>

        <p class="text-gray-500 mb-8">
            Oops! Halaman yang Anda cari mungkin telah dihapus, diubah namanya, atau tidak pernah ada.
        </p>

        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-bold rounded-lg text-white bg-[#065f46] hover:bg-[#047857] transition shadow-md">
            &larr; Kembali ke Dashboard
        </a>
    </div>

</body>
</html>
