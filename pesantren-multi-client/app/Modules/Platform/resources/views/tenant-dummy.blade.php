<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pesantren</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen font-sans">
    <div class="bg-white p-10 rounded-xl shadow-lg text-center max-w-md w-full">
        <div class="w-16 h-16 bg-[#065f46] text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">S</div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Berhasil Login!</h1>
        <p class="text-gray-600">Anda sekarang berada di area:</p>
        <p class="text-xl font-bold text-[#065f46] mb-6">{{ $client->name }}</p>
        <p class="text-sm text-gray-400 mb-8">(Halaman ini akan dikerjakan oleh Orang B & C)</p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full bg-red-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-red-700 transition">Logout</button>
        </form>
    </div>
</body>
</html>
