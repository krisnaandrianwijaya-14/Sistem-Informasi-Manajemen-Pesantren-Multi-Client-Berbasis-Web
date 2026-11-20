<x-layouts.admin :title="'Edit Data Guru'">
    @php
        $clientParam = request()->route('client');
    @endphp

    <div class="min-h-screen bg-[#F5F5F5] -m-6 p-6">
        <div class="max-w-3xl mx-auto space-y-5">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <a href="{{ client_route('client.admin.teachers.index', ['client' => $clientParam]) }}"
                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-emerald-700 transition">
                        &larr; Kembali ke Data Guru
                    </a>
                    <h2 class="text-lg font-bold text-gray-800 mt-1">Edit Data Guru</h2>
                </div>

                <form action="{{ client_route('client.admin.teachers.destroy', ['client' => $clientParam, 'id' => $teacher->id]) }}"
                      method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus data guru {{ $teacher->name }}?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 rounded-lg text-sm font-semibold bg-white text-red-600 border border-red-200 hover:bg-red-50 transition">
                        Hapus Guru
                    </button>
                </form>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ client_route('client.admin.teachers.update', ['client' => $clientParam, 'id' => $teacher->id]) }}"
                  method="POST"
                  class="bg-white rounded-xl border border-gray-200/80 shadow-sm p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">NIP / No. Induk Pegawai</label>
                    <input type="text" name="employee_number" value="{{ old('employee_number', $teacher->employee_number) }}"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nama Lengkap Guru *</label>
                    <input type="text" name="name" value="{{ old('name', $teacher->name) }}" required
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">No. HP / Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $teacher->phone) }}"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Status *</label>
                    <select name="status" required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="active" {{ old('status', $teacher->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $teacher->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                    <a href="{{ client_route('client.admin.teachers.index', ['client' => $clientParam]) }}"
                       class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-5 py-2 rounded-lg text-sm font-semibold bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>