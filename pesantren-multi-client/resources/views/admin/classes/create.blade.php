<x-layouts.admin :title="'Tambah Kelas'">
    @php
        $clientParam = request()->route('client');
        $clientSlug = $clientParam?->slug ?? $clientParam ?? 'al-hikmah';
    @endphp

    <div class="min-h-screen bg-[#F5F5F5] -m-6 p-6">
        <div class="max-w-3xl mx-auto space-y-5">
            <div>
                <a href="{{ client_route('client.admin.classes.index', ['client' => $clientSlug]) }}"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-emerald-700 transition">
                    &larr; Kembali ke Data Kelas
                </a>
                <h2 class="text-lg font-bold text-gray-800 mt-1">Tambah Kelas</h2>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ client_route('client.admin.classes.store', ['client' => $clientSlug]) }}"
                  method="POST"
                  class="bg-white rounded-xl border border-gray-200/80 shadow-sm p-6 space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nama Kelas *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="Contoh: Ula 1B / Kelas 7A"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Tahun Ajaran *</label>
                    <input type="text" name="academic_year" value="{{ old('academic_year', '2026/2027') }}" required
                           placeholder="Contoh: 2026/2027"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    @error('academic_year') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Tingkat</label>
                    <input type="text" name="level" value="{{ old('level') }}"
                           placeholder="Contoh: Ula / Wustha / Ulya"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    @error('level') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                    <a href="{{ client_route('client.admin.classes.index', ['client' => $clientSlug]) }}"
                       class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-5 py-2 rounded-lg text-sm font-semibold bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>