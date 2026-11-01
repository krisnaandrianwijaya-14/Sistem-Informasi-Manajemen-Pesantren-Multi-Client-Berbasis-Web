<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        // Mengambil data santri beserta kelasnya
        $students = Student::with('schoolClass')->orderBy('name')->paginate(10);
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
            'nis' => 'required|string|unique:students,nis',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'class_id' => 'nullable|exists:classes,id',
        ]);

        // Karena ini multi-client, pastikan client_id terisi.
        // Jika menggunakan Global Scope / Trait BelongsToClient, baris ini mungkin otomatis.
        // $validated['client_id'] = auth()->user()->client_id;

        Student::create($validated);

        return redirect()->route('admin.students.index')->with('success', 'Santri berhasil ditambahkan.');
    }
}
