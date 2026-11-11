<x-layouts.admin title="Rekap Absensi">
    <div class="max-w-6xl mx-auto">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Rekap Absensi Santri</h2>
                <p class="text-sm text-gray-500 mt-1">Akumulasi kehadiran santri berdasarkan data yang telah diinput oleh guru.</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
            <form action="{{ url('/client/demo/admin/attendance-recap') }}" method="GET" class="flex items-end gap-4">
                <div class="flex-1 max-w-xs">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Filter Kelas</label>
                    <select name="class_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Semua Kelas</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">Terapkan Filter</button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Nama Santri</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Kelas</th>
                        <th class="px-6 py-4 text-xs font-semibold text-green-600 uppercase text-center">Hadir</th>
                        <th class="px-6 py-4 text-xs font-semibold text-blue-600 uppercase text-center">Izin</th>
                        <th class="px-6 py-4 text-xs font-semibold text-amber-600 uppercase text-center">Sakit</th>
                        <th class="px-6 py-4 text-xs font-semibold text-red-600 uppercase text-center">Alpa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recaps as $recap)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $recap['student']->full_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $recap['student']->classes->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-center font-bold text-green-600">{{ $recap['hadir'] }}</td>
                        <td class="px-6 py-4 text-sm text-center font-bold text-blue-600">{{ $recap['izin'] }}</td>
                        <td class="px-6 py-4 text-sm text-center font-bold text-amber-600">{{ $recap['sakit'] }}</td>
                        <td class="px-6 py-4 text-sm text-center font-bold text-red-600">{{ $recap['alpa'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada data absensi yang tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>