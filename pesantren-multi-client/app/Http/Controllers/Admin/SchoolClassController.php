<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::withoutGlobalScopes()->orderBy('name')->paginate(20);
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('admin.classes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:80',
            'academic_year' => 'required|string|max:20',
            'level' => 'nullable|string|max:30',
        ]);

        // Isi client_id dari route {client}
        $client = $request->route('client');
        $clientId = $client instanceof \App\Models\Client
            ? $client->id
            : DB::table('clients')->where('slug', (string) $client)->value('id');

        if (! $clientId) {
            $clientId = 1;
        }

        $validated['client_id'] = $clientId;

        SchoolClass::create($validated);
        return redirect(client_route('client.admin.classes.index'))
            ->with('success', 'Kelas ditambahkan.');
    }

    public function edit(string $id)
    {
        $class = SchoolClass::withoutGlobalScopes()->findOrFail($id);
        return view('admin.classes.edit', compact('class'));
    }

    public function update(Request $request, string $id)
    {
        $class = SchoolClass::withoutGlobalScopes()->findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:80',
            'academic_year' => 'required|string|max:20',
            'level' => 'nullable|string|max:30',
        ]);
        $class->update($validated);
        return redirect(client_route('client.admin.classes.index'))
            ->with('success', 'Kelas diperbarui.');
    }

    public function destroy(string $id)
    {
        SchoolClass::withoutGlobalScopes()->findOrFail($id)->delete();
        return redirect(client_route('client.admin.classes.index'))
            ->with('success', 'Kelas dihapus.');
    }
}