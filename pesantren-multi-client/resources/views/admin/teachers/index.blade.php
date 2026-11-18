<x-layouts.admin title="Data Guru">
    @php $clientSlug = request()->route('client')?->slug ?? request()->route('client') ?? 'demo'; @endphp
    <div class="mb-4">
        <a href="{{ url('/client/' . $clientSlug . '/admin/teachers/create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-bold">+ Tambah Guru</a>
    </div>
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">NIP</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">Nama</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">No. HP</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">Status</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($teachers as $t)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm">{{ $t->employee_number ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm font-medium">{{ $t->name }}</td>
                    <td class="px-4 py-3 text-sm">{{ $t->phone ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm">{{ $t->status }}</td>
                    <td class="px-4 py-3 text-sm text-right space-x-2">
                        <a href="{{ url('/client/' . $clientSlug . '/admin/teachers/' . $t->id . '/edit') }}" class="text-emerald-600 hover:underline">Edit</a>
                        <form action="{{ url('/client/' . $clientSlug . '/admin/teachers/' . $t->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus guru ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">Belum ada data guru.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $teachers->links() }}</div>
</x-layouts.admin>