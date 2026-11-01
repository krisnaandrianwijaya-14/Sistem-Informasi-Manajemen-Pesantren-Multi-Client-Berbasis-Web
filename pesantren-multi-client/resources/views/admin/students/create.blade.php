<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Santri</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-xl font-bold mb-4">Tambah Santri Baru</h1>
        <form action="{{ route('admin.students.store') }}" method="POST">
            @csrf
            <div class="mb-4"><label>NIS</label><input type="text" name="nis" class="w-full border p-2" required></div>
            <div class="mb-4"><label>Nama</label><input type="text" name="name" class="w-full border p-2" required></div>
            <div class="mb-4"><label>Gender</label><select name="gender" class="w-full border p-2" required><option value="L">Laki-laki</option><option value="P">Perempuan</option></select></div>
            <div class="mb-4"><label>Kelas</label><select name="class_id" class="w-full border p-2"><option value="">-- Pilih --</option>@foreach($classes as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select></div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
        </form>
    </div>
</body>
</html>
