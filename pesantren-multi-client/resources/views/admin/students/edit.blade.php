<x-layouts.admin title="Edit Santri">
    @php $clientSlug = request()->route('client')?->slug ?? request()->route('client') ?? 'demo'; @endphp
    <form action="{{ client_route('client.admin.students.update', ['client' => $clientSlug, 'student' => $student]) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-3xl">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">NIS *</label>
                <input type="text" name="nis" value="{{ old('nis', $student->nis) }}" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm" required>
                @error('nis') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Lengkap *</label>
                <input type="text" name="full_name" value="{{ old('full_name', $student->full_name) }}" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Gender *</label>
                <select name="gender" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm" required>
                    <option value="L" {{ old('gender', $student->gender) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('gender', $student->gender) === 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Kelas</label>
                <select name="class_id" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">
                    <option value="">- Pilih Kelas -</option>
                    @foreach($classes as $k)
                        <option value="{{ $k->id }}" {{ old('class_id', $student->class_id) == $k->id ? 'selected' : '' }}>{{ $k->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">
                    <option value="active" {{ old('status', $student->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $student->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">No. HP</label>
                <input type="text" name="phone_number" value="{{ old('phone_number', $student->phone_number) }}" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea name="address" rows="3" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">{{ old('address', $student->address) }}</textarea>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg text-sm font-bold">Perbarui</button>
            <a href="{{ client_route('client.admin.students.index', ['client' => $clientSlug]) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg text-sm font-bold">Batal</a>
        </div>
    </form>
</x-layouts.admin>