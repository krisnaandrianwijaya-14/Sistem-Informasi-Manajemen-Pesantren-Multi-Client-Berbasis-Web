<x-layouts.admin title="Detail Profil Santri">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Detail Profil Santri</h2>
                <p class="text-sm text-gray-500 mt-1">Informasi lengkap biodata santri.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ client_route('students.edit', $student ?? $id) }}" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700">Edit Data</a>
                <a href="{{ client_route('students.index') }}" class="text-gray-500 hover:text-gray-800 text-sm font-medium">&larr; Kembali</a>
            </div>
        </div>

        @if(isset($student))
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase">Nama Lengkap</p>
                    <p class="text-base font-medium text-gray-800">{{ $student->full_name ?? $student->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase">NIS</p>
                    <p class="text-base text-gray-800">{{ $student->nis ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase">Kelas</p>
                    <p class="text-base text-gray-800">{{ $student->classes->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase">Alamat</p>
                    <p class="text-base text-gray-800">{{ $student->address ?? '-' }}</p>
                </div>
            </div>
        @endif
    </div>
</x-layouts.admin>
