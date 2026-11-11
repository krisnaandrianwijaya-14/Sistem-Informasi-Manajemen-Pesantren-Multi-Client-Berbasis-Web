<x-layouts.admin title="Jadwal Pelajaran">
    <div class="max-w-6xl mx-auto">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Jadwal Pelajaran</h2>
                <p class="text-sm text-gray-500 mt-1">Atur jadwal kelas dan guru pengampu.</p>
            </div>
            <a href="/client/demo/admin/schedules/create" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2 shadow-sm" style="text-decoration: none;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Jadwal
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Hari</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Waktu</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Kelas</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Mata Pelajaran</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Guru</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($schedules as $schedule)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $schedule->day }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $schedule->classes->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $schedule->subjects->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $schedule->teacher->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ url('/client/demo/admin/attendance/' . $schedule->id) }}" class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1.5 rounded-md text-xs font-bold transition-colors">Isi Absensi</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada jadwal pelajaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>