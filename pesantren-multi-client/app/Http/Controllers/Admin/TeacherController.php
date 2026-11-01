<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::orderBy('name')->get();
        return view('admin.teachers.index', compact('teachers'));
    }
    public function create()
    {
        return view('admin.teachers.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
        ]);
        Teacher::create($validated);
        return redirect()->route('client.admin.teachers.index')->with('success', 'Guru berhasil ditambahkan.');
    }
}
