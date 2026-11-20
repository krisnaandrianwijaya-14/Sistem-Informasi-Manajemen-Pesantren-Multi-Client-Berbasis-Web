<x-layouts.admin :title="'Edit Kelas'">
    @php
        $clientParam = request()->route('client');
        $clientSlug = $clientParam?->slug ?? $clientParam ?? 'al-hikmah';
    @endphp

    <div class="min-h-screen bg-[#F5F5F5] -m-6 p-6">
        <div class="max-w-3xl mx-auto space-y-5">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <a href="{{ url('/client/' . $clientSlug . '/admin/classes') }}"
                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-emerald-700 transition">
                        &larr; Kembali ke Data Kelas
                    </a>
                    <h2 class="text-lg font-bold text-gray-800 mt-1">Edit Kelas</h2>
                </div>

                <form action="{{ url('/client/' . $clientSlug . '/admin/classes/' . $class->id) }}"
                      method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus kelas {{ $class->name }}?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 rounded-lg text-sm font-semibold bg-white text-red-600 border border-red-200 hover:bg-red-50 transition">
                        Hapus Kelas
                    </button>
                </form>
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

            <form action="{{ url('/client/' . $clientSlug . '/admin/classes/' . $class->id) }}"
                  method="POST"
                  class="bg-white rounded-xl border border-gray-200/80 shadow-sm p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nama Kelas *</label>
                    <input type="text" name="name" value="{{ old('name', $class->name) }}" required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Tahun Ajaran *</label>
                    <input type="text" name="academic_year" value="{{ old('academic_year', $class->academic_year) }}" required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Tingkat</label>
                    <input type="text" name="level" value="{{ old('level', $class->level) }}"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                    <a href="{{ url('/client/' . $clientSlug . '/admin/classes') }}"
                       class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-5 py-2 rounded-lg text-sm font-semibold bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>