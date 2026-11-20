<x-layouts.admin>
    <div class="p-6 max-w-6xl mx-auto">
        <!-- Breadcrumb -->
        <div class="text-sm text-gray-500 mb-4">
            Data Santri / <span class="text-gray-800 font-medium">Detail Profil Santri</span>
        </div>

        <!-- Kartu Profil Utama -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex justify-between items-center mb-6">
            <div class="flex items-center gap-4">
                <!-- Avatar Placeholder -->
                <img src="https://ui-avatars.com/api/?name={{ urlencode($student->full_name) }}&background=0D8ABC&color=fff&size=80" alt="Foto Santri" class="w-20 h-20 rounded-full object-cover border-2 border-green-100">
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-2xl font-bold text-gray-800">{{ $student->full_name }}</h2>
                        <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded-full uppercase">{{ $student->status }}</span>
                    </div>
                    <p class="text-gray-500 text-sm mt-1">
                        NIS: {{ $student->nis }} • {{ $student->schoolClass->name ?? 'Belum ada kelas' }} • {{ $student->dormitory_name ?? 'Gedung A' }}
                    </p>
                </div>
            </div>
            <button onclick="window.print()" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 font-medium text-sm transition">
                Cetak Biodata
            </button>
        </div>

        <!-- Tabs Navigasi -->
        <div class="border-b border-gray-200 mb-6 flex gap-6 overflow-x-auto">
            <a href="#" class="pb-3 border-b-2 border-green-600 text-green-600 font-semibold">Profil</a>
            <a href="#" class="pb-3 text-gray-500 hover:text-gray-700 font-medium">Akademik</a>
            <a href="#" class="pb-3 text-gray-500 hover:text-gray-700 font-medium">Absensi</a>
            <a href="#" class="pb-3 text-gray-500 hover:text-gray-700 font-medium">Asrama</a>
            <a href="#" class="pb-3 text-gray-500 hover:text-gray-700 font-medium">Perizinan</a>
            <a href="#" class="pb-3 text-gray-500 hover:text-gray-700 font-medium">Keuangan</a>
        </div>

        <!-- Grid Konten -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informasi Pribadi & Keluarga -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Informasi Pribadi & Keluarga</h3>
                <div class="grid grid-cols-2 gap-y-4 gap-x-2 text-sm">
                    <div class="text-gray-500">Tempat, Tanggal Lahir</div>
                    <div class="font-medium text-gray-800">{{ $student->birth_place ?? '-' }}, {{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d F Y') : '-' }}</div>
                    
                    <div class="text-gray-500">Jenis Kelamin</div>
                    <div class="font-medium text-gray-800">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                    
                    <div class="text-gray-500">Ayah Kandung</div>
                    <div class="font-medium text-gray-800">{{ $student->father_name ?? '-' }}</div>
                    
                    <div class="text-gray-500">Ibu Kandung</div>
                    <div class="font-medium text-gray-800">{{ $student->mother_name ?? '-' }}</div>
                    
                    <div class="text-gray-500">Kontak Orang Tua</div>
                    <div class="font-medium text-gray-800">{{ $student->phone_number ?? '-' }}</div>
                    
                    <div class="text-gray-500">Alamat Asal</div>
                    <div class="font-medium text-gray-800">{{ $student->address ?? '-' }}</div>
                </div>
            </div>

            <!-- Status Keuangan & SPP (Mockup sesuai Figma) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Status Keuangan & SPP</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600">Syahriah Maret 2026</span>
                        <span class="bg-green-100 text-green-700 font-bold px-3 py-1 rounded-full text-xs">LUNAS</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600">Uang Makan Maret 2026</span>
                        <span class="bg-green-100 text-green-700 font-bold px-3 py-1 rounded-full text-xs">LUNAS</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600">Gedung / Sarpras</span>
                        <span class="bg-green-100 text-green-700 font-bold px-3 py-1 rounded-full text-xs">LUNAS</span>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t">
                    <a href="{{ route('client.admin.students.edit', ['client' => request()->route('client'), 'id' => $student->id]) }}" class="w-full block text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 rounded-lg transition">
                        Edit Data Santri
                    </a>
                </div>
            </div>
        </div>

        <!-- Tombol Kembali -->
        <div class="mt-8">
            <a href="{{ route('client.admin.students.index', ['client' => request()->route('client')]) }}" class="text-sm text-gray-500 hover:text-gray-800 font-medium">
                &larr; Kembali ke Daftar Santri
            </a>
        </div>
    </div>
</x-layouts.admin>