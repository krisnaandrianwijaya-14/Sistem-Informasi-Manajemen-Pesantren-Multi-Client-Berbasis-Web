<x-layouts.admin>
    <div class="p-6 max-w-4xl mx-auto">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Edit Data Santri</h2>
            <p class="text-gray-500 text-sm mt-1">Perbarui informasi profil santri di bawah ini.</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6 shadow-sm">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8">
            <form action="{{ route('client.admin.students.update', ['client' => request()->route('client'), 'id' => $student->id]) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">NIS <span class="text-red-500">*</span></label>
                            <input type="text" name="nis" value="{{ old('nis', $student->nis) }}" class="w-full border-gray-300 rounded-lg p-2.5 border focus:ring-2 focus:ring-green-500 focus:border-green-500 transition bg-gray-50" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="full_name" value="{{ old('full_name', $student->full_name) }}" class="w-full border-gray-300 rounded-lg p-2.5 border focus:ring-2 focus:ring-green-500 focus:border-green-500 transition bg-gray-50" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select name="gender" class="w-full border-gray-300 rounded-lg p-2.5 border focus:ring-2 focus:ring-green-500 focus:border-green-500 transition bg-gray-50">
                                <option value="L" {{ $student->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ $student->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Kelas</label>
                            <select name="class_id" class="w-full border-gray-300 rounded-lg p-2.5 border focus:ring-2 focus:ring-green-500 focus:border-green-500 transition bg-gray-50">
                                <option value="">- Pilih Kelas -</option>
                                @foreach($classes ?? [] as $class)
                                    <option value="{{ $class->id }}" {{ $student->class_id == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tempat Lahir</label>
                            <input type="text" name="birth_place" value="{{ old('birth_place', $student->birth_place) }}" class="w-full border-gray-300 rounded-lg p-2.5 border focus:ring-2 focus:ring-green-500 focus:border-green-500 transition bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date', $student->birth_date) }}" class="w-full border-gray-300 rounded-lg p-2.5 border focus:ring-2 focus:ring-green-500 focus:border-green-500 transition bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Ayah</label>
                            <input type="text" name="father_name" value="{{ old('father_name', $student->father_name) }}" class="w-full border-gray-300 rounded-lg p-2.5 border focus:ring-2 focus:ring-green-500 focus:border-green-500 transition bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Ibu</label>
                            <input type="text" name="mother_name" value="{{ old('mother_name', $student->mother_name) }}" class="w-full border-gray-300 rounded-lg p-2.5 border focus:ring-2 focus:ring-green-500 focus:border-green-500 transition bg-gray-50">
                        </div>
                    </div>
                </div>

                <!-- Full Width Row -->
                <div class="grid grid-cols-1 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">No. HP / Kontak Orang Tua</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $student->phone_number) }}" class="w-full border-gray-300 rounded-lg p-2.5 border focus:ring-2 focus:ring-green-500 focus:border-green-500 transition bg-gray-50">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Lengkap</label>
                        <textarea name="address" rows="3" class="w-full border-gray-300 rounded-lg p-2.5 border focus:ring-2 focus:ring-green-500 focus:border-green-500 transition bg-gray-50">{{ old('address', $student->address) }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 border-t pt-6">
                    <a href="{{ route('client.admin.students.index', ['client' => request()->route('client')]) }}" class="bg-white border border-gray-300 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-50 font-medium transition">
                        Batal
                    </a>
                    <button type="submit" class="bg-[#149e6c] hover:bg-green-700 text-white font-bold px-6 py-2.5 rounded-lg shadow-sm transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>