<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - SiSantri</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen font-sans">
    <div class="w-full max-w-md p-8 bg-white rounded-2xl shadow-xl border border-gray-100">
        <div class="mb-6 text-center">
            <h2 class="text-2xl font-extrabold text-gray-900">Lupa Password?</h2>
            <p class="text-sm text-gray-500 mt-2">Masukkan email Anda untuk menerima instruksi pembuatan password baru.</p>
        </div>
        @if (session('status'))
            <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 p-3 rounded-lg">{{ session('status') }}</div>
        @endif
        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">E-mail Terdaftar</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#065f46] focus:border-transparent transition"
                    placeholder="ustadz@domain.com">
                @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="w-full py-3 px-4 rounded-lg shadow-md text-sm font-bold text-white bg-[#065f46] hover:bg-[#047857] transition">
                Kirim Instruksi
            </button>
            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-500 hover:text-[#065f46]">&larr; Kembali ke Login</a>
            </div>
        </form>
    </div>
</body>
</html>
