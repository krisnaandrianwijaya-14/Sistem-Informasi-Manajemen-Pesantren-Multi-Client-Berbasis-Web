<x-layouts.admin :title="'Detail Kelas'">
    @php
        $clientParam = request()->route('client');
        $clientSlug = $clientParam?->slug ?? $clientParam ?? 'al-hikmah';
    @endphp

    <div class="min-h-screen bg-[#F5F5F5] -m-6 p-6">
        <div class="max-w-6xl mx-auto space-y-5">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <a href="{{ url('/client/' . $clientSlug . '/admin/classes') }}"
                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-emerald-700 transition">
                        &larr; Kembali ke Data Kelas
                    </a>
                    <h2 class="text-lg font-bold text-gray-800 mt-1">Detail Kelas</h2>
                </div>

                <div class="flex items-center gap-2.5">
                    <a href="{{ url('/client/' . $clientSlug . '/admin/classes/' . $class->id . '/edit') }}"
                       class="px-4 py-2 rounded-lg text-sm font-semibold bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm transition">
                        Edit Kelas
                    </a>

                    <form action="{{ url('/client/' . $clientSlug . '/admin/classes/' . $class->id) }}"
                          method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus kelas {{ $class->name }}?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-4 py-2 rounded-lg text-sm font-semibold bg-white text-red-600 border border-red-200 hover:bg-red-50 transition">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>

            <!-- Kartu Informasi Kelas -->
            <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm p-5">
                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-gray-100 pb-4">
                    <div>
                        <div class="flex items-center gap-2.5">
                            <h3 class="text-xl font-bold text-gray-900">{{ $class->name }}</h3>
                            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">
                                {{ $class->academic_year ?? '-' }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            Tingkat: <span class="font-semibold text-gray-700">{{ $class->level ?? '-' }}</span>
                            &bull; Wali Kelas: <span class="font-semibold text-gray-700">{{ $class->homeroomTeacher->name ?? '-' }}</span>
                        </p>
                    </div>

                    <div class="bg-[#F5F5F5] px-4 py-2.5 rounded-xl text-right">
                        <div class="text-xs text-gray-500">Jumlah Santri</div>
                        <div class="text-lg font-bold text-emerald-700">{{ $class->students->count() }}</div>
                    </div>
                </div>

                <!-- Daftar Santri di Kelas -->
                <div class="pt-4">
                    <h4 class="text-sm font-bold text-gray-800 mb-3">Daftar Santri Terdaftar</h4>
                    <div class="overflow-x-auto rounded-lg border border-gray-100">
                        <table class="w-full text-left">
                            <thead class="bg-[#F5F5F5]/80 border-b border-gray-200/70">
                                <tr>
                                    <th class="px-4 py-2.5 text-xs font-semibold text-gray-600 uppercase">NIS</th>
                                    <th class="px-4 py-2.5 text-xs font-semibold text-gray-600 uppercase">Nama Santri</th>
                                    <th class="px-4 py-2.5 text-xs font-semibold text-gray-600 uppercase">L/P</th>
                                    <th class="px-4 py-2.5 text-xs font-semibold text-gray-600 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($class->students as $st)
                                    <tr class="hover:bg-emerald-50/30">
                                        <td class="px-4 py-2.5 text-sm text-gray-700">{{ $st->nis ?? '-' }}</td>
                                        <td class="px-4 py-2.5 text-sm font-semibold text-gray-800">{{ $st->full_name }}</td>
                                        <td class="px-4 py-2.5 text-sm text-gray-700">{{ $st->gender ?? '-' }}</td>
                                        <td class="px-4 py-2.5 text-sm">
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">
                                                {{ $st->status ?? 'active' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">
                                            Belum ada santri di kelas ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>