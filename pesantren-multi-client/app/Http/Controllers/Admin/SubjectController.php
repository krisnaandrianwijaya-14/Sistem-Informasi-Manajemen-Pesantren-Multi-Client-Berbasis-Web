<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::orderBy('name')->paginate(20);
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('admin.subjects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        Subject::create($validated);
        return redirect(client_route('client.admin.subjects.index'))
            ->with('success', 'Mapel ditambahkan.');
    }

    public function edit(string $id)
    {
        $subject = Subject::findOrFail($id);
        return view('admin.subjects.edit', compact('subject'));
    }

    public function update(Request $request, string $id)
    {
        $subject = Subject::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $subject->update($validated);
        return redirect(client_route('client.admin.subjects.index'))
            ->with('success', 'Mapel diperbarui.');
    }

    public function destroy(string $id)
    {
        Subject::findOrFail($id)->delete();
        return redirect(client_route('client.admin.subjects.index'))
            ->with('success', 'Mapel dihapus.');
    }
}