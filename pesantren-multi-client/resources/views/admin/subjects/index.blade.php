<!DOCTYPE html><html><head><script src="https://cdn.tailwindcss.com"></script></head><body class="bg-gray-100 p-6">
<h1 class="text-2xl font-bold mb-4">Mata Pelajaran (Kitab)</h1>
<a href="{{ route('client.admin.subjects.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">+ Tambah Mapel</a>
<table class="w-full bg-white border"><thead class="bg-gray-200"><tr><th class="p-2 border">Kode</th><th class="p-2 border">Nama Kitab/Mapel</th></tr></thead><tbody>
@foreach($subjects as $s)<tr><td class="p-2 border">{{ $s->code ?? '-' }}</td><td class="p-2 border">{{ $s->name }}</td></tr>@endforeach
</tbody></table></body></html>
