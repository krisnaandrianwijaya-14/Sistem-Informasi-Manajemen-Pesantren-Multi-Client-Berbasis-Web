<x-layouts.admin title="Detail Profil Santri">
    @php
        $clientSlug = request()->route('client')?->slug ?? request()->route('client') ?? 'demo';
    @endphp

    <div class="max-w-4xl mx-auto space-y-6">
        {{-- HEADER --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center text-2xl font-bold text-emerald-700">
                        {{ strtoupper(substr($student->full_name ?? '-', 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-xl font-bold text-gray-800">{{ $student->full_name ?? '-' }}</h2>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $student->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ strtoupper($student->status ?? 'ACTIVE') }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500">
                            NIS: {{ $student->nis ?? '-' }}
                            @if($student->classRoom) • Kelas {{ $student->classRoom->name }} @endif
                            @if($student->dormitory_name) • {{ $student->dormitory_name }} {{ $student->room_number ? 'K-' . $student->room_number : '' }} @endif
                        </p>
                    </div>
                </div>
                <button type="button" class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50">
                    Cetak Biodata
                </button>
            </div>
        </div>

        {{-- TAB --}}
        <div class="border-b border-gray-200">
            <nav class="flex gap-6 text-sm font-semibold">
                <a href="#" class="py-3 text-emerald-700 border-b-2 border-emerald-700">Profil</a>
                <a href="#" class="py-3 text-gray-500 hover:text-gray-800">Akademik</a>
                <a href="#" class="py-3 text-gray-500 hover:text-gray-800">Absensi</a>
                <a href="#" class="py-3 text-gray-500 hover:text-gray-800">Asrama</a>
                <a href="#" class="py-3 text-gray-500 hover:text-gray-800">Perizinan</a>
                <a href="#" class="py-3 text-gray-500 hover:text-gray-800">Keuangan</a>
            </nav>
        </div>

        {{-- 2 KOLOM --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-4">Informasi Pribadi & Keluarga</h3>
                <dl class="grid grid-cols-2 gap-y-3 text-sm">
                    <dt class="text-gray-500">Tempat, Tanggal Lahir</dt>
                    <dd class="font-medium text-gray-800">{{ $student->birth_place ?? '-' }}, {{ $student->birth_date ? $student->birth_date->translatedFormat('d F Y') : '-' }}</dd>
                    <dt class="text-gray-500">Jenis Kelamin</dt>
                    <dd class="font-medium text-gray-800">{{ $student->gender === 'L' ? 'Laki-laki' : ($student->gender === 'P' ? 'Perempuan' : '-') }}</dd>
                    <dt class="text-gray-500">Ayah Kandung</dt>
                    <dd class="font-medium text-gray-800">{{ $student->father_name ?? '-' }}</dd>
                    <dt class="text-gray-500">Ibu Kandung</dt>
                    <dd class="font-medium text-gray-800">{{ $student->mother_name ?? '-' }}</dd>
                    <dt class="text-gray-500">Kontak Orang Tua</dt>
                    <dd class="font-medium text-gray-800">{{ $student->phone_number ?? '-' }}</dd>
                    <dt class="text-gray-500">Alamat Asal</dt>
                    <dd class="font-medium text-gray-800">{{ $student->address ?? '-' }}</dd>
                </dl>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-4">Status Keuangan & SPP</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500">SPP Bulan Ini</p>
                            <p class="font-medium">Belum ada data</p>
                        </div>
                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">N/A</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500">Tagihan Lainnya</p>
                            <p class="font-medium">Belum ada data</p>
                        </div>
                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">N/A</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ url('/client/' . $clientSlug . '/admin/students') }}"
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-2 rounded-lg text-sm font-bold">
                &larr; Kembali
            </a>
            <a href="{{ url('/client/' . $clientSlug . '/admin/students/' . $student->id . '/edit') }}"
               class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg text-sm font-bold">
                Edit Data
            </a>
        </div>
    </div>
</x-layouts.admin>