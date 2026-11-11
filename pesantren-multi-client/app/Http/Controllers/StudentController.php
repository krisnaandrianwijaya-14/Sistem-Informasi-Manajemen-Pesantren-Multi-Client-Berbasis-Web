<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'nis' => 'required|string|unique:students,nis',
            'gender' => 'required|in:L,P',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'class_id' => 'nullable|integer',
            'dormitory_name' => 'nullable|string|max:255',
            'room_number' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        // 2. Tambahkan data default
        $validated['status'] = 'active';
        $validated['tenant_id'] = 1; // Paksa 1 agar tidak error

        // 3. Simpan ke Database
        Student::create($validated);

        // 4. Redirect kembali ke tabel dengan pesan sukses
        return redirect('/client/demo/admin/students')->with('success', 'Data santri berhasil disimpan.');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete(); // Soft delete
        return redirect('/client/demo/admin/students')->with('success', 'Data santri berhasil dihapus.');
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('client.admin.students-edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'nis' => 'required|string|unique:students,nis,' . $student->id,
            'gender' => 'required|in:L,P',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'class_id' => 'nullable|integer',
            'dormitory_name' => 'nullable|string|max:255',
            'room_number' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $student->update($validated);

        return redirect('/client/demo/admin/students')->with('success', 'Data santri berhasil diperbarui.');
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('client.admin.students-edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'nis' => 'required|string|unique:students,nis,' . $student->id,
            'gender' => 'required|in:L,P',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'class_id' => 'nullable|integer',
            'dormitory_name' => 'nullable|string|max:255',
            'room_number' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $student->update($validated);

        return redirect('/client/demo/admin/students')->with('success', 'Data santri berhasil diperbarui.');
    }
}