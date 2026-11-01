<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex h-screen">
    <aside class="w-64 bg-[#0f766e] text-white flex flex-col">
        <div class="p-4 text-xl font-bold border-b border-teal-700">Pesantren Demo</div>
        <nav class="flex-1 p-4 space-y-1 text-sm">
            <a href="{{ url('/client/demo/admin/dashboard') }}" class="block px-4 py-2 hover:bg-teal-800 rounded font-semibold">Dashboard</a>
            <a href="{{ url('/client/demo/students') }}" class="block px-4 py-2 hover:bg-teal-800 rounded">Data Santri</a>
            <a href="{{ url('/client/demo/classes') }}" class="block px-4 py-2 hover:bg-teal-800 rounded">Data Kelas</a>
            <a href="{{ url('/client/demo/teachers') }}" class="block px-4 py-2 hover:bg-teal-800 rounded">Data Guru</a>
            <a href="{{ url('/client/demo/subjects') }}" class="block px-4 py-2 hover:bg-teal-800 rounded">Mata Pelajaran</a>
            <a href="{{ url('/client/demo/schedules') }}" class="block px-4 py-2 hover:bg-teal-800 rounded">Jadwal Pelajaran</a>
        </nav>
    </aside>
    <main class="flex-1 p-6">
        <h1 class="text-2xl font-bold">Assalamu'alaikum, Admin Test</h1>
        <p class="text-gray-600">Dashboard admin Pesantren.</p>
    </main>
</body>
</html>
