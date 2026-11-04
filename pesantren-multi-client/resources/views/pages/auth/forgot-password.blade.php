<x-layouts::auth>
    <div class="w-full max-w-md p-8 bg-white rounded-2xl shadow-xl border border-gray-100 mx-auto">
        <div class="mb-6 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-[#065f46] text-white mb-4 shadow-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900">Lupa Password?</h2>
            <p class="text-sm text-gray-500 mt-2">Masukkan email Anda untuk menerima instruksi pembuatan password baru.</p>
        </div>

        @if (session('status'))
            <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 p-3 rounded-lg text-center">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit="sendPasswordResetLink" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">E-mail Terdaftar</label>
                <input wire:model="email" id="email" type="email" required autofocus
                    class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#065f46] focus:border-transparent transition"
                    placeholder="ustadz@domain.com">
                @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full py-3 px-4 rounded-lg shadow-md text-sm font-bold text-white bg-[#065f46] hover:bg-[#047857] transition">
                Kirim Instruksi
            </button>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-500 hover:text-[#065f46]" wire:navigate>&larr; Kembali ke Login</a>
            </div>
        </form>
    </div>
</x-layouts::auth>  
