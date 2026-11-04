<x-layouts::auth>
    <div class="w-full max-w-md p-8 bg-white rounded-2xl shadow-xl border border-gray-100 mx-auto">
        <div class="mb-6 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-[#065f46] text-white mb-4 shadow-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900">Atur Sandi Baru</h2>
        </div>

        <form wire:submit="resetPassword" class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">E-mail Terdaftar</label>
                <input wire:model="email" id="email" type="email" required class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 focus:ring-2 focus:ring-[#065f46]">
                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password Baru</label>
                <input wire:model="password" id="password" type="password" required class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 focus:ring-2 focus:ring-[#065f46]">
                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
                <input wire:model="password_confirmation" id="password_confirmation" type="password" required class="block w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-900 focus:ring-2 focus:ring-[#065f46]">
            </div>

            <button type="submit" class="w-full py-3 px-4 rounded-lg shadow-md text-sm font-bold text-white bg-[#065f46] hover:bg-[#047857] transition mt-4">
                Simpan Kata Sandi
            </button>
        </form>
    </div>
</x-layouts::auth>
