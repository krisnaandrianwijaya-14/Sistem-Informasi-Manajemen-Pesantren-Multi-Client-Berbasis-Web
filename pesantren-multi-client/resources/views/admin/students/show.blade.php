<x-layouts.admin :title="'Detail Profil Santri'">
    @php
        $clientParam = request()->route('client');
        $isActive = ($student->status ?? 'active') === 'active';
    @endphp

    <div class="min-h-screen bg-[#F5F5F5] -m-6 p-6">
        <div class="max-w-6xl mx-auto space-y-5">
            <!-- Navigasi Kembali & Aksi -->
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <a href="{{ client_route('client.admin.students.index', ['client' => $clientParam]) }}"
                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-emerald-700 transition">
                        &larr; Kembali ke Data Santri
                    </a>
                    <h2 class="text-lg font-bold text-gray-800 mt-1">Detail Profil Santri</h2>
                </div>

                <div class="flex items-center gap-2.5">
                    <a href="{{ client_route('client.admin.students.edit', ['client' => $clientParam, 'id' =>$student->id]) }}"
                       class="px-4 py-2 rounded-lg text-sm font-semibold bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm transition">
                        Edit Data
                    </a>

                    <form action="{{ client_route('client.admin.students.destroy', ['client' => $clientParam, 'id' =>$student->id]) }}"
                          method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus data santri {{ $student->full_name }}?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-4 py-2 rounded-lg text-sm font-semibold bg-white text-red-600 border border-red-200 hover:bg-red-50 transition">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>

            <!-- Kartu Profil Utama (Ala Figma) -->
            <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm p-5">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center text-xl font-bold shrink-0">
                            {{ strtoupper(substr($student->full_name ?? 'S', 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <h3 class="text-xl font-bold text-gray-900">{{ $student->full_name }}</h3>
                                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full {{ $isActive ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ strtoupper($student->status ?? 'active') }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                NIS: <span class="font-semibold text-gray-700">{{ $student->nis }}</span>
                                &bull; Kelas: <span class="font-semibold text-gray-700">{{ $student->classRoom->name ?? '-' }}</span>
                                &bull; L/P: <span class="font-semibold text-gray-700">{{ $student->gender }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tab Navigasi -->
                <div class="mt-6 border-b border-gray-200 flex items-center gap-6 text-sm">
                    <button type="button" onclick="setDetailTab('pribadi')" id="btn-pribadi"
                            class="pb-3 border-b-2 border-emerald-600 text-emerald-700 font-semibold">
                        Informasi Pribadi &amp; Akademik
                    </button>
                    <button type="button" onclick="setDetailTab('keluarga')" id="btn-keluarga"
                            class="pb-3 border-b-2 border-transparent text-gray-500 hover:text-emerald-600 font-medium">
                        Orang Tua &amp; Kontak
                    </button>
                </div>

                <!-- Tab 1: Pribadi & Akademik -->
                <div id="panel-pribadi" class="pt-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="bg-[#F5F5F5] rounded-xl p-4 space-y-3">
                        <h4 class="text-sm font-bold text-gray-800">Informasi Pribadi</h4>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">NIS</span>
                            <span class="font-medium text-gray-800">{{ $student->nis }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Nama Lengkap</span>
                            <span class="font-medium text-gray-800">{{ $student->full_name }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Jenis Kelamin</span>
                            <span class="font-medium text-gray-800">{{ $student->gender === 'L' ? 'Laki-laki (L)' : 'Perempuan (P)' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Tempat, Tanggal Lahir</span>
                            <span class="font-medium text-gray-800">
                                {{ $student->birth_place ?? '-' }}{{ $student->birth_date ? ', ' . \Carbon\Carbon::parse($student->birth_date)->format('d M Y') : '' }}
                            </span>
                        </div>
                        <div class="flex justify-between text-sm gap-4">
                            <span class="text-gray-500 shrink-0">Alamat</span>
                            <span class="font-medium text-gray-800 text-right">{{ $student->address ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="bg-[#F5F5F5] rounded-xl p-4 space-y-3">
                        <h4 class="text-sm font-bold text-gray-800">Informasi Akademik</h4>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Kelas</span>
                            <span class="font-medium text-gray-800">{{ $student->classRoom->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Status</span>
                            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full {{ $isActive ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-200 text-gray-700' }}">
                                {{ $student->status ?? 'active' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Keluarga & Kontak -->
                <div id="panel-keluarga" class="pt-5 hidden">
                    <div class="bg-[#F5F5F5] rounded-xl p-4 space-y-3 max-w-2xl">
                        <h4 class="text-sm font-bold text-gray-800">Data Orang Tua &amp; Kontak</h4>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Nama Ayah</span>
                            <span class="font-medium text-gray-800">{{ $student->father_name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Nama Ibu</span>
                            <span class="font-medium text-gray-800">{{ $student->mother_name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">No. HP / Telepon</span>
                            <span class="font-medium text-gray-800">{{ $student->phone_number ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setDetailTab(tab) {
            ['pribadi', 'keluarga'].forEach(t => {
                document.getElementById('panel-' + t).classList.add('hidden');
                const btn = document.getElementById('btn-' + t);
                btn.classList.remove('border-emerald-600', 'text-emerald-700', 'font-semibold');
                btn.classList.add('border-transparent', 'text-gray-500', 'font-medium');
            });
            document.getElementById('panel-' + tab).classList.remove('hidden');
            const active = document.getElementById('btn-' + tab);
            active.classList.remove('border-transparent', 'text-gray-500', 'font-medium');
            active.classList.add('border-emerald-600', 'text-emerald-700', 'font-semibold');
        }
    </script>
</x-layouts.admin>