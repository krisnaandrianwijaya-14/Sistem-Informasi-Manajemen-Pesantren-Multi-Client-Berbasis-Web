<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Jadwal Mengajar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Jadwal Mengajar - Ustadz Ahmad</h1>
        <a href="{{ url('/client/demo/admin/dashboard') }}" class="text-blue-500 underline">Kembali ke Admin (Test)</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">{{ session('success') }}</div>
    @endif

    <table class="w-full bg-white border">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2 border">Hari</th>
                <th class="p-2 border">Jam</th>
                <th class="p-2 border">Kelas</th>
                <th class="p-2 border">Mata Pelajaran</th>
                <th class="p-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php
                $days = [1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu',7=>'Minggu'];
            @endphp

            @forelse($schedules as $s)
            <tr>
                <td class="p-2 border">{{ $days[$s->day_of_week] ?? '-' }}</td>
                <td class="p-2 border">{{ $s->start_time }} - {{ $s->end_time }}</td>
                <td class="p-2 border">{{ $s->schoolClass->name ?? '-' }}</td>
                <td class="p-2 border">{{ $s->subject->name ?? '-' }}</td>
                <td class="p-2 border text-center">
                    <a href="{{ route('client.guru.attendances.create', $s->id) }}" class="bg-teal-600 text-white px-3 py-1 rounded text-sm">Isi Absensi</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="p-4 text-center border text-gray-500">Anda belum memiliki jadwal mengajar.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
