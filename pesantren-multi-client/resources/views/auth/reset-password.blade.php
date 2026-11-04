<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SiSantri</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen font-sans">
    <div class="w-full max-w-md p-8 bg-white rounded-2xl shadow-xl border border-gray-100">
        <div class="mb-6 text-center">
            <h2 class="text-2xl font-extrabold text-gray-900">Atur Sandi Baru</h2>
        </div>
        <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">E-mail Terdaftar</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required readonly class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-gray-100 text-gray-500">
                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password Baru</label>
                <input id="password" type="password" name="password" required class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#065f46] focus:border-transparent transition">
                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#065f46] focus:border-transparent transition">
            </div>
            <button type="submit" class="w-full py-3 px-4 rounded-lg shadow-md text-sm font-bold text-white bg-[#065f46] hover:bg-[#047857] transition mt-4">
                Simpan Kata Sandi
            </button>
        </form>
    </div>
</body>
</html>
