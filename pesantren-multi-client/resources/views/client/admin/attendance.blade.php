<x-layouts.admin title="Form Absensi">
    <div class="max-w-6xl mx-auto">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-100 pb-4 mb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Presensi Harian Santri</h2>
                    <div class="flex gap-4 text-sm text-gray-500 mt-1">
                        <p>Mapel: <span class="font-bold text-gray-800">{{ $schedule->subject->name ?? '-' }}</span></p>
                        <p>Kelas: <span class="font-bold text-gray-800">{{ $schedule->classRoom->name ?? '-' }}</span></p>
                        <p>Tanggal: <span class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}</span></p>
                    </div>
                </div>
                <a href="{{ client_route('client.admin.schedules.index') }}" class="text-gray-500 hover:text-gray-800 text-sm font-medium">&larr; Kembali ke Jadwal</a>
            </div>

            <form action="{{ client_route('client.admin.attendance.store', $schedule->id) }}" method="POST">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Nama Lengkap</th>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase text-center">Status Kehadiran</th>
                                <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($students as $student)
                            @php
                                $existing = $existingAttendances->get($student->id);
                                $currentStatus = $existing ? $existing->status : 'hadir';
                                $currentNote = $existing ? $existing->note : '';
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <p class="text-sm font-semibold text-gray-800">{{ $student->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $student->nis }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        @foreach(['hadir' => 'Hadir', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alpa' => 'Alpa'] as $val => $label)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="attendance[{{ $student->id }}][status]" value="{{ $val }}" class="sr-only peer" {{ $currentStatus === $val ? 'checked' : '' }}>
                                                <span class="px-3 py-1 text-xs font-semibold border rounded-full peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 hover:bg-gray-100">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <input type="text" name="attendance[{{ $student->id }}][note]" value="{{ $currentNote }}" class="w-full text-sm border-gray-300 rounded-md shadow-sm" placeholder="Opsional">
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="px-4 py-8 text-center text-gray-500">Belum ada santri di kelas ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-lg text-sm font-bold">Simpan Presensi</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>