<x-layouts.admin title="Tambah Santri">
    @php $clientSlug = request()->route('client')?->slug ?? request()->route('client') ?? 'demo'; @endphp
    <form action="{{ client_route('client.admin.students.store', ['client' => $clientSlug]) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-3xl">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">NIS *</label>
                <input type="text" name="nis" value="{{ old('nis') }}" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm @error('nis') border-red-500 @enderror" required>
                @error('nis') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Lengkap *</label>
                <input type="text" name="full_name" value="{{ old('full_name') }}" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm @error('full_name') border-red-500 @enderror" required>
                @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Gender *</label>
                <select name="gender" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm" required>
                    <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Kelas</label>
                <select name="class_id" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">
                    <option value="">- Pilih Kelas -</option>
                    @foreach($classes as $k)
                        <option value="{{ $k->id }}" {{ old('class_id') == $k->id ? 'selected' : '' }}>{{ $k->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                <input type="text" name="birth_place" value="{{ old('birth_place') }}" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Ayah</label>
                <input type="text" name="father_name" value="{{ old('father_name') }}" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Ibu</label>
                <input type="text" name="mother_name" value="{{ old('mother_name') }}" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">No. HP</label>
                <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea name="address" rows="3" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">{{ old('address') }}</textarea>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg text-sm font-bold">Simpan</button>
            <a href="{{ client_route('client.admin.students.index', ['client' => $clientSlug]) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg text-sm font-bold">Batal</a>
        </div>
    </form>
</x-layouts.admin>
