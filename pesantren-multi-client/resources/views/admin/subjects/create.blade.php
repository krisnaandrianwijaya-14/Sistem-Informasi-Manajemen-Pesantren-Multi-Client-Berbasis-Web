<x-layouts.admin title="Tambah Mapel">
    @php $clientSlug = request()->route('client')?->slug ?? request()->route('client') ?? 'demo'; @endphp
    <form action="{{ client_route('client.admin.subjects.store', ['client' => $clientSlug]) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-3xl">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Mata Pelajaran *</label>
            <input type="text" name="name" value="{{ old('name') }}" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm" required>
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg text-sm font-bold">Simpan</button>
            <a href="{{ client_route('client.admin.subjects.index', ['client' => $clientSlug]) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg text-sm font-bold">Batal</a>
        </div>
    </form>
</x-layouts.admin>