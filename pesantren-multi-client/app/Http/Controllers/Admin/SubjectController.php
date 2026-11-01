<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::orderBy('name')->get();
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
            'code' => 'nullable|string|max:50',
        ]);
        Subject::create($validated);
        return redirect()->route('client.admin.subjects.index')->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }
}
