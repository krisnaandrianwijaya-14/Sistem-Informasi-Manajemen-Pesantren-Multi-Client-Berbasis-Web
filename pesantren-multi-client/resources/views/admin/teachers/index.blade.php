<!DOCTYPE html><html><head><script src="https://cdn.tailwindcss.com"></script></head><body class="bg-gray-100 p-6">
<h1 class="text-2xl font-bold mb-4">Data Guru</h1>
<a href="{{ route('client.admin.teachers.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">+ Tambah Guru</a>
<table class="w-full bg-white border"><thead class="bg-gray-200"><tr><th class="p-2 border">NIP</th><th class="p-2 border">Nama</th></tr></thead><tbody>
@foreach($teachers as $t)<tr><td class="p-2 border">{{ $t->nip ?? '-' }}</td><td class="p-2 border">{{ $t->name }}</td></tr>@endforeach
</tbody></table></body></html>
