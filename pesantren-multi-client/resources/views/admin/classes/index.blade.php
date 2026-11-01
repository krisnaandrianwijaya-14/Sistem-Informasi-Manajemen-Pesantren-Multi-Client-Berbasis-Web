<x-app-layout>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Manajemen Kelas</h1>
        <a href="{{ route('admin.classes.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ Tambah Kelas</a>
        
        <table class="w-full mt-4 border-collapse border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">Nama Kelas</th>
                    <th class="border p-2">Level</th>
                </tr>
            </thead>
            <tbody>
                @foreach($classes as $class)
                <tr>
                    <td class="border p-2">{{ $class->name }}</td>
                    <td class="border p-2">{{ $class->level }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
