<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Jadwal Pelajaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-2xl font-bold mb-4">Jadwal Pelajaran</h1>

    <a href="{{ route('client.admin.schedules.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">+ Tambah Jadwal</a>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">{{ session('success') }}</div>
    @endif

    <table class="w-full bg-white border">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2 border">Hari</th>
                <th class="p-2 border">Jam</th>
                <th class="p-2 border">Kelas</th>
                <th class="p-2 border">Mapel</th>
                <th class="p-2 border">Guru</th>
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
                <td class="p-2 border">{{ $s->teacher->name ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="p-2 text-center border">Belum ada jadwal.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
