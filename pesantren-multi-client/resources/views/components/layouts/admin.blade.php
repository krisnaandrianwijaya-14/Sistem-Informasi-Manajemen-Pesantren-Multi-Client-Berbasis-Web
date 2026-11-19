<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Admin' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
    @livewireStyles
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden">
    @php
        $clientSlug = request()->route('client')?->slug
            ?? request()->route('client')
            ?? auth()->user()?->defaultClientSlug()
            ?? 'demo';
    @endphp
    <aside class="w-64 bg-emerald-700 text-white flex flex-col h-full shadow-lg z-20">
        <div class="h-16 flex items-center px-6 border-b border-emerald-600">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-white text-emerald-700 flex items-center justify-center font-bold rounded">S</div>
                <span class="text-xl font-bold tracking-wide">SiSantri</span>
            </div>
        </div>
        <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
            <p class="px-3 text-xs font-semibold text-emerald-300 uppercase tracking-wider mb-2">Menu</p>
            <a href="{{ client_route('client.admin.students.index', ['client' => $clientSlug]) }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->is('*students*') ? 'bg-emerald-800 text-white' : 'text-emerald-100 hover:bg-emerald-600' }} rounded-lg">
                <span class="font-medium">Data Santri</span>
            </a>
            <a href="{{ client_route('client.admin.teachers.index', ['client' => $clientSlug]) }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->is('*teachers*') ? 'bg-emerald-800 text-white' : 'text-emerald-100 hover:bg-emerald-600' }} rounded-lg">
                <span class="font-medium">Data Guru</span>
            </a>
            <a href="{{ client_route('client.admin.classes.index', ['client' => $clientSlug]) }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->is('*classes*') ? 'bg-emerald-800 text-white' : 'text-emerald-100 hover:bg-emerald-600' }} rounded-lg">
                <span class="font-medium">Data Kelas</span>
            </a>
            <a href="{{ client_route('client.admin.subjects.index', ['client' => $clientSlug]) }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->is('*subjects*') ? 'bg-emerald-800 text-white' : 'text-emerald-100 hover:bg-emerald-600' }} rounded-lg">
                <span class="font-medium">Mata Pelajaran</span>
            </a>
            <a href="{{ client_route('client.admin.schedules.index', ['client' => $clientSlug]) }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->is('*schedules*') ? 'bg-emerald-800 text-white' : 'text-emerald-100 hover:bg-emerald-600' }} rounded-lg">
                <span class="font-medium">Jadwal Pelajaran</span>
            </a>
            <a href="{{ client_route('client.admin.attendance.recap', ['client' => $clientSlug]) }}" class="flex items-center gap-3 px-3 py-2.5 {{ request()->is('*attendance-recap*') ? 'bg-emerald-800 text-white' : 'text-emerald-100 hover:bg-emerald-600' }} rounded-lg">
                <span class="font-medium">Rekap Absensi</span>
            </a>
        </div>
        <div class="h-16 border-t border-emerald-600 flex items-center px-6 gap-3 bg-emerald-800">
            <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center font-bold">A</div>
            <div>
                <p class="text-sm font-bold leading-tight">{{ auth()->user()->name ?? 'Admin' }}</p>
                <p class="text-xs text-emerald-300">Pengelola Pesantren</p>
            </div>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-full overflow-hidden relative">
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 z-10">
            <h1 class="text-xl font-bold text-gray-800">{{ $title ?? 'Dashboard' }}</h1>
            <div class="flex items-center gap-4">
                <a href="{{ route('logout.get') }}" class="text-sm font-semibold text-gray-600 hover:text-red-500">Logout</a>
            </div>
        </header>
        <div class="flex-1 overflow-y-auto p-8">
            {{ $slot }}
        </div>
    </main>
    @livewireScripts
</body>
</html>