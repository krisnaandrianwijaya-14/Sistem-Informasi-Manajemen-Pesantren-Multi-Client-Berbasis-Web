<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Form Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-xl font-bold mb-2">Form Absensi Kelas</h1>
        <div class="mb-4 text-gray-600">
            <p>Kelas: <strong>{{ $schedule->schoolClass->name }}</strong> | Mapel: <strong>{{ $schedule->subject->name }}</strong></p>
            <p>Tanggal: <strong>{{ $date }}</strong></p>
        </div>

        <form action="{{ route('client.guru.attendances.store', $schedule->id) }}" method="POST">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">

            <table class="w-full border mb-4">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-2 border w-12">No</th>
                        <th class="p-2 border text-left">Nama Santri</th>
                        <th class="p-2 border" colspan="4">Status Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $index => $student)
                    @php
                        $existing = $existingAttendances->get($student->id);
                        $currentStatus = $existing ? $existing->status : 'hadir';
                    @endphp
                    <tr>
                        <td class="p-2 border text-center">{{ $index + 1 }}</td>
                        <td class="p-2 border">{{ $student->name }}</td>
                        
                        <td class="p-2 border text-center">
                            <label><input type="radio" name="attendance[{{ $student->id }}]" value="hadir" {{ $currentStatus == 'hadir' ? 'checked' : '' }}> Hadir</label>
                        </td>
                        <td class="p-2 border text-center">
                            <label><input type="radio" name="attendance[{{ $student->id }}]" value="sakit" {{ $currentStatus == 'sakit' ? 'checked' : '' }}> Sakit</label>
                        </td>
                        <td class="p-2 border text-center">
                            <label><input type="radio" name="attendance[{{ $student->id }}]" value="izin" {{ $currentStatus == 'izin' ? 'checked' : '' }}> Izin</label>
                        </td>
                        <td class="p-2 border text-center">
                            <label><input type="radio" name="attendance[{{ $student->id }}]" value="alpa" {{ $currentStatus == 'alpa' ? 'checked' : '' }}> Alpa</label>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="p-4 text-center border">Belum ada data santri di kelas ini.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="flex justify-between">
                <a href="{{ route('client.guru.attendances.index') }}" class="text-gray-500 hover:underline">Kembali ke Jadwal</a>
                <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded">Simpan Absensi</button>
            </div>
        </form>
    </div>
</body>
</html>
