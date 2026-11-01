<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tambah Jadwal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-xl font-bold mb-4">Tambah Jadwal Pelajaran</h1>

        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('client.admin.schedules.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label>Kelas</label>
                <select name="class_id" class="w-full border p-2" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label>Mata Pelajaran</label>
                <select name="subject_id" class="w-full border p-2" required>
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($subjects as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label>Guru</label>
                <select name="teacher_id" class="w-full border p-2" required>
                    <option value="">-- Pilih Guru --</option>
                    @foreach($teachers as $t)
                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label>Hari</label>
                <select name="day_of_week" class="w-full border p-2" required>
                    <option value="1">Senin</option>
                    <option value="2">Selasa</option>
                    <option value="3">Rabu</option>
                    <option value="4">Kamis</option>
                    <option value="5">Jumat</option>
                    <option value="6">Sabtu</option>
                    <option value="7">Minggu</option>
                </select>
            </div>

            <div class="mb-4">
                <label>Jam Mulai</label>
                <input type="time" name="start_time" class="w-full border p-2" required>
            </div>

            <div class="mb-4">
                <label>Jam Selesai</label>
                <input type="time" name="end_time" class="w-full border p-2" required>
            </div>

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('client.admin.schedules.index') }}" class="ml-2 text-gray-500">Batal</a>
        </form>
    </div>
</body>
</html>
