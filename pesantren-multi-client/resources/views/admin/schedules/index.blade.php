<x-layouts.admin title="Jadwal Pelajaran">
    @php $clientSlug = request()->route('client')?->slug ?? request()->route('client') ?? 'demo'; @endphp
    <div class="mb-4">
        <a href="{{ url('/client/' . $clientSlug . '/admin/schedules/create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-bold">+ Tambah Jadwal</a>
    </div>
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">Hari</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">Jam</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">Kelas</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">Mapel</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase">Guru</th>
                    <th class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($schedules as $s)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm">{{ ucfirst($s->day) }}</td>
                    <td class="px-4 py-3 text-sm">{{ $s->start_time }} - {{ $s->end_time }}</td>
                    <td class="px-4 py-3 text-sm">{{ $s->classRoom->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm">{{ $s->subject->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm">{{ $s->teacher->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-right">
                        <form action="{{ url('/client/' . $clientSlug . '/admin/schedules/' . $s->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus jadwal ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada jadwal.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.admin>