<x-layouts.admin title="Tambah Santri">
    <div class="max-w-4xl mx-auto">
        
        <!-- Pesan Error Validasi -->
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p class="font-bold">Terjadi Kesalahan!</p>
                <ul class="list-disc pl-5 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Tambah Santri Baru</h2>
                <p class="text-sm text-gray-500 mt-1">Isi formulir di bawah ini dengan lengkap.</p>
            </div>
            <a href="{{ url('/client/demo/admin/students') }}" class="text-gray-500 hover:text-gray-800 text-sm font-medium">&larr; Kembali</a>
        </div>

        <!-- Form Standar Laravel -->
        <form action="{{ url('/client/demo/admin/students') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- BAGIAN 1: DATA PRIBADI -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-6 py-3 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">1. Data Pribadi</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Nama santri" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Induk Siswa (NIS) <span class="text-red-500">*</span></label>
                        <input type="text" name="nis" value="{{ old('nis') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Contoh: 20260401" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                        <select name="gender" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                            <input type="text" name="birth_place" value="{{ old('birth_place') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Kota">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- BAGIAN 2: AKADEMIK & ASRAMA -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-6 py-3 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">2. Akademik & Asrama</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelas Madrasah</label>
                        <select name="class_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Pilih Kelas</option>
                            <!-- Pastikan Anda mengirim data $classes dari controller jika menggunakan standar form, atau hardcode sementara -->
                            <option value="1" {{ old('class_id') == '1' ? 'selected' : '' }}>Kelas 1A - Ula</option>
                            <option value="2" {{ old('class_id') == '2' ? 'selected' : '' }}>Kelas 2B - Wustho</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gedung Asrama</label>
                        <input type="text" name="dormitory_name" value="{{ old('dormitory_name') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Mis: Gedung Al-Ghazali">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Kamar</label>
                        <input type="text" name="room_number" value="{{ old('room_number') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Mis: K-03">
                    </div>
                </div>
            </div>

            <!-- BAGIAN 3: DATA ORANG TUA -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-6 py-3 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">3. Data Orang Tua / Wali</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ayah Kandung</label>
                        <input type="text" name="father_name" value="{{ old('father_name') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ibu Kandung</label>
                        <input type="text" name="mother_name" value="{{ old('mother_name') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Handphone/WhatsApp</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Rumah Lengkap</label>
                        <textarea name="address" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('address') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pb-8">
                <a href="{{ url('/client/demo/admin/students') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 shadow-sm">Simpan Data Santri</button>
            </div>
        </form>
    </div>
</x-layouts.admin>