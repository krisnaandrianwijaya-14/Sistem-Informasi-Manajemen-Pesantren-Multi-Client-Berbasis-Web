<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::orderBy('name')->paginate(20);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'employee_number' => 'nullable|string|max:30',
            'phone' => 'nullable|string|max:20',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $validated['status'] = 'active';

        Teacher::create($validated);

        return redirect(client_route('client.admin.teachers.index'))
            ->with('success', 'Guru berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, string $id)
    {
        $teacher = Teacher::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'employee_number' => 'nullable|string|max:30',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);
        $teacher->update($validated);
        return redirect(client_route('client.admin.teachers.index'))
            ->with('success', 'Data guru diperbarui.');
    }

    public function destroy(string $id)
    {
        Teacher::findOrFail($id)->delete();
        return redirect(client_route('client.admin.teachers.index'))
            ->with('success', 'Guru dihapus.');
    }
}