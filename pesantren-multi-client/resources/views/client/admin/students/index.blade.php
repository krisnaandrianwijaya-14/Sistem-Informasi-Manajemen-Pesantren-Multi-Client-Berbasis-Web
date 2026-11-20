<x-layouts.admin>
    <div class="p-6 bg-white rounded-lg shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Data Santri</h2>
            <a href="{{ route('client.admin.students.create', ['client' => request()->route('client')]) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 shadow-sm transition">
                + Tambah Santri
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="p-3 font-semibold text-gray-600">NIS</th>
                        <th class="p-3 font-semibold text-gray-600">NAMA</th>
                        <th class="p-3 font-semibold text-gray-600">L/P</th>
                        <th class="p-3 font-semibold text-gray-600">KELAS</th>
                        <th class="p-3 font-semibold text-gray-600">STATUS</th>
                        <th class="p-3 font-semibold text-gray-600">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="p-3">{{ $student->nis }}</td>
                            <td class="p-3">{{ $student->full_name }}</td>
                            <td class="p-3">{{ $student->gender }}</td>
                            <td class="p-3">{{ $student->schoolClass->name ?? '-' }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                    {{ $student->status }}
                                </span>
                            </td>
                            <td class="p-3">
                                <div class="flex gap-3 text-sm">
                                    <!-- KUNCI PERBAIKAN: Menggunakan 'id' bukan 'student' -->
                                    <a href="{{ route('client.admin.students.show', ['client' => request()->route('client'), 'id' => $student->id]) }}" class="text-blue-500 hover:underline">Detail</a>
                                    <a href="{{ route('client.admin.students.edit', ['client' => request()->route('client'), 'id' => $student->id]) }}" class="text-yellow-500 hover:underline">Edit</a>
                                    <form action="{{ route('client.admin.students.destroy', ['client' => request()->route('client'), 'id' => $student->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-500">Belum ada data santri.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $students->links() }}
        </div>
    </div>
</x-layouts.admin>