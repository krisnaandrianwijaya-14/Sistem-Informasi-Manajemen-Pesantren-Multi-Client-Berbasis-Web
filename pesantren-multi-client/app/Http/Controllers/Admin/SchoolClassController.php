<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::orderBy('name')->paginate(20);
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('admin.classes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        SchoolClass::create($validated);
        return redirect(client_route('client.admin.classes.index'))
            ->with('success', 'Kelas ditambahkan.');
    }

    public function edit(string $id)
    {
        $class = SchoolClass::findOrFail($id);
        return view('admin.classes.edit', compact('class'));
    }

    public function update(Request $request, string $id)
    {
        $class = SchoolClass::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $class->update($validated);
        return redirect(client_route('client.admin.classes.index'))
            ->with('success', 'Kelas diperbarui.');
    }

    public function destroy(string $id)
    {
        SchoolClass::findOrFail($id)->delete();
        return redirect(client_route('client.admin.classes.index'))
            ->with('success', 'Kelas dihapus.');
    }
}