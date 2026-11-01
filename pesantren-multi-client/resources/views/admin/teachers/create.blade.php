<!DOCTYPE html><html><head><script src="https://cdn.tailwindcss.com"></script></head><body class="bg-gray-100 p-10">
<div class="max-w-lg mx-auto bg-white p-6 rounded shadow"><h1 class="text-xl font-bold mb-4">Tambah Guru</h1>
<form action="{{ route('client.admin.teachers.store') }}" method="POST">@csrf
<div class="mb-4"><label>Nama Lengkap</label><input type="text" name="name" class="w-full border p-2" required></div>
<div class="mb-4"><label>NIP (Opsional)</label><input type="text" name="nip" class="w-full border p-2"></div>
<button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
</form></div></body></html>
