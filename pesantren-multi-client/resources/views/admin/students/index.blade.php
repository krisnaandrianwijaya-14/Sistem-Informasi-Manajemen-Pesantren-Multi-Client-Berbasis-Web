<x-layouts.admin title="Data Santri">
    @php $clientSlug = request()->route('client')?->slug ?? request()->route('client') ?? 'demo'; @endphp
    <div class="mb-4">
        <a href="{{ url('/client/' . $clientSlug . '/admin/students/create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-bold">
            + Tambah Santri
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">NIS</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">Nama</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">L/P</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">Kelas</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">Status</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($students as $s)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm">{{ $s->nis }}</td>
                    <td class="px-4 py-3 text-sm font-medium">{{ $s->full_name }}</td>
                    <td class="px-4 py-3 text-sm">{{ $s->gender }}</td>
                    <td class="px-4 py-3 text-sm">{{ $s->classRoom->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm">
                        <span class="px-2 py-1 text-xs rounded-full {{ $s->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $s->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-right space-x-2">
                        <a href="{{ url('/client/' . $clientSlug . '/admin/students/' . $s->id) }}" class="text-blue-600 hover:underline">Detail</a>
                        <a href="{{ url('/client/' . $clientSlug . '/admin/students/' . $s->id . '/edit') }}" class="text-emerald-600 hover:underline">Edit</a>
                        <form action="{{ url('/client/' . $clientSlug . '/admin/students/' . $s->id) }}" method="POST" class="inline" onsubmit="return confirm('Nonaktifkan santri ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada data santri.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $students->links() }}</div>
</x-layouts.admin>