<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Santri</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex h-screen">
    <aside class="w-64 bg-[#0f766e] text-white flex flex-col">
        <div class="p-4 text-xl font-bold border-b border-teal-700">Pesantren Demo</div>
        <nav class="flex-1 p-4 space-y-2 text-sm">
            <a href="{{ url('/client/demo/admin/dashboard') }}" class="block px-4 py-2 hover:bg-teal-800 rounded">Dashboard</a>
            <a href="#" class="block px-4 py-2 bg-teal-800 rounded font-semibold">Data Santri</a>
            <a href="{{ url('/client/demo/classes') }}" class="block px-4 py-2 hover:bg-teal-800 rounded">Data Kelas</a>
        </nav>
    </aside>
    <main class="flex-1 p-6">
        <h1 class="text-2xl font-bold mb-4">Manajemen Santri</h1>
        <a href="{{ route('admin.students.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">+ Tambah Santri</a>
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">{{ session('success') }}</div>
        @endif
        <table class="w-full bg-white border">
            <thead class="bg-gray-200">
                <tr><th class="p-2 border">NIS</th><th class="p-2 border">Nama</th><th class="p-2 border">L/P</th><th class="p-2 border">Kelas</th></tr>
            </thead>
            <tbody>
                @forelse($students as $s)
                <tr>
                    <td class="p-2 border">{{ $s->nis }}</td>
                    <td class="p-2 border">{{ $s->name }}</td>
                    <td class="p-2 border">{{ $s->gender }}</td>
                    <td class="p-2 border">{{ $s->schoolClass->name ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="p-2 text-center border">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </main>
</body>
</html>
