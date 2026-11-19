<x-layouts.admin title="Tambah Jadwal">
    @php $clientSlug = request()->route('client')?->slug ?? request()->route('client') ?? 'demo'; @endphp
    <form action="{{ client_route('client.admin.schedules.store', ['client' => $clientSlug]) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-3xl">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Kelas *</label>
                <select name="class_id" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm" required>
                    <option value="">- Pilih Kelas -</option>
                    @foreach($classes as $k)
                        <option value="{{ $k->id }}" {{ old('class_id') == $k->id ? 'selected' : '' }}>{{ $k->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Mapel *</label>
                <select name="subject_id" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm" required>
                    <option value="">- Pilih Mapel -</option>
                    @foreach($subjects as $s)
                        <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Guru *</label>
                <select name="teacher_id" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm" required>
                    <option value="">- Pilih Guru -</option>
                    @foreach($teachers as $t)
                        <option value="{{ $t->id }}" {{ old('teacher_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Hari *</label>
                <select name="day" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm" required>
                    @foreach(['senin','selasa','rabu','kamis','jumat','sabtu'] as $d)
                        <option value="{{ $d }}" {{ old('day') === $d ? 'selected' : '' }}>{{ ucfirst($d) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jam Mulai *</label>
                <input type="time" name="start_time" value="{{ old('start_time') }}" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jam Selesai *</label>
                <input type="time" name="end_time" value="{{ old('end_time') }}" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm" required>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg text-sm font-bold">Simpan</button>
            <a href="{{ client_route('client.admin.schedules.index', ['client' => $clientSlug]) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg text-sm font-bold">Batal</a>
        </div>
    </form>
</x-layouts.admin>