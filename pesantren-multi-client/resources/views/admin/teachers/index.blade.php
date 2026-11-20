<x-layouts.admin :title="'Data Guru'">
    @php
        $clientParam = request()->route('client');
        $clientSlug = $clientParam?->slug ?? $clientParam ?? 'al-hikmah';
    @endphp

    <div class="min-h-screen bg-[#F5F5F5] -m-6 p-6">
        <div class="max-w-7xl mx-auto space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Daftar Guru</h2>
                    <p class="text-xs text-gray-500">Kelola data pengajar, nomor induk, kontak, dan status aktif.</p>
                </div>
                <a href="{{ url('/client/' . $clientSlug . '/admin/teachers/create') }}"
                   class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm transition">
                    + Tambah Guru
                </a>
            </div>

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-[#F5F5F5]/80 border-b border-gray-200/70">
                        <tr>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">NIP</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">No. HP</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($teachers as $t)
                        <tr class="hover:bg-emerald-50/30 transition">
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $t->employee_number ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ $t->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $t->phone ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full {{ ($t->status ?? 'active') === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $t->status ?? 'active' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ url('/client/' . $clientSlug . '/admin/teachers/' . $t->id . '/edit') }}"
                                       class="px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-600 text-white hover:bg-emerald-700 transition">
                                        Edit
                                    </a>
                                    <form action="{{ url('/client/' . $clientSlug . '/admin/teachers/' . $t->id) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Yakin ingin menghapus guru {{ $t->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-2.5 py-1 rounded-md text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">Belum ada data guru.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($teachers, 'links'))
                <div class="pt-1">{{ $teachers->links() }}</div>
            @endif
        </div>
    </div>
</x-layouts.admin>