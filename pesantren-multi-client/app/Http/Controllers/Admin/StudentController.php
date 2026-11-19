<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('classRoom')->orderBy('full_name')->paginate(10);
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $classes = SchoolClass::orderBy('name')->get();
        return view('admin.students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:50|unique:students,nis',
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'class_id' => 'nullable|exists:classes,id',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $validated['status'] = 'active';
        Student::create($validated);

        return redirect(client_route('client.admin.students.index'))
            ->with('success', 'Santri berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        Log::info('show() dipanggil', ['id' => $id, 'type' => gettype($id)]);

        $student = Student::withoutGlobalScopes()->with('classRoom')->find($id);
        Log::info('show() hasil find', ['found' => $student !== null, 'id' => $student?->id]);

        if (! $student) {
            abort(404, "Santri dengan ID {$id} tidak ditemukan.");
        }

        return view('admin.students.show', compact('student'));
    }

    public function edit(string $id)
    {
        $student = Student::withoutGlobalScopes()->find($id);
        if (! $student) {
            abort(404, "Santri dengan ID {$id} tidak ditemukan.");
        }
        $classes = SchoolClass::orderBy('name')->get();
        return view('admin.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, string $id)
    {
        $student = Student::withoutGlobalScopes()->find($id);
        if (! $student) {
            abort(404, "Santri dengan ID {$id} tidak ditemukan.");
        }

        $validated = $request->validate([
            'nis' => ['required', 'string', 'max:50', Rule::unique('students', 'nis')->ignore($student->id)],
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'class_id' => 'nullable|exists:classes,id',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $student->update($validated);

        return redirect(client_route('client.admin.students.index'))
            ->with('success', 'Santri berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $student = Student::withoutGlobalScopes()->find($id);
        if (! $student) {
            abort(404, "Santri dengan ID {$id} tidak ditemukan.");
        }
        $student->delete();
        return redirect(client_route('client.admin.students.index'))
            ->with('success', 'Santri berhasil dinonaktifkan.');
    }
}