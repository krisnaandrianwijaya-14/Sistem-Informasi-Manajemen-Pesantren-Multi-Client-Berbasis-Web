<x-app-layout>
    <div class="p-6 max-w-lg">
        <h1 class="text-2xl font-bold mb-4">Tambah Kelas Baru</h1>
        <form action="{{ route('admin.classes.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block mb-1">Nama Kelas</label>
                <input type="text" name="name" class="w-full border p-2 rounded" required placeholder="Contoh: Ula 1A">
            </div>
            <div class="mb-4">
                <label class="block mb-1">Level (Opsional)</label>
                <input type="text" name="level" class="w-full border p-2 rounded" placeholder="Contoh: Ula">
            </div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('admin.classes.index') }}" class="ml-2 text-gray-500">Batal</a>
        </form>
    </div>
</x-app-layout>
