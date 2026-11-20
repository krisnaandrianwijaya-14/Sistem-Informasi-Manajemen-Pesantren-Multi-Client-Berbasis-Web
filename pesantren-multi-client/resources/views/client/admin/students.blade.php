<x-layouts.admin title="Data Santri">
    <div class="max-w-6xl mx-auto">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Data Santri</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola data seluruh santri aktif pesantren.</p>
            </div>
            <a href="{{ client_route('students.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Santri
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif

        @php
            $studentList = $students ?? \App\Models\Student::with('classes')->latest()->get();
        @endphp

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">NIS</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Nama Lengkap</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">L/P</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Kelas</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Asrama / Kamar</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($studentList as $student)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $student->nis ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $student->full_name ?? $student->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $student->gender ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $student->classes->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $student->dormitory_name ?? '-' }} {{ $student->room_number ? '(' . $student->room_number . ')' : '' }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ client_route('students.show', $student->id) }}" class="text-gray-600 hover:text-gray-900 text-xs font-semibold">Detail</a>
                            <a href="{{ client_route('students.edit', $student->id) }}" class="text-emerald-600 hover:text-emerald-800 text-xs font-semibold">Edit</a>
                            <form action="{{ client_route('students.destroy', $student->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data santri ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-semibold">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada data santri.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
