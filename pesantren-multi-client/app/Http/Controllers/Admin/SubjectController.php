<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::withoutGlobalScopes()->orderBy('name')->paginate(20);
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

        $client = $request->route('client');
        $clientId = $client instanceof \App\Models\Client
            ? $client->id
            : DB::table('clients')->where('slug', (string) $client)->value('id');

        if (! $clientId) {
            $clientId = 1;
        }

        $validated['client_id'] = $clientId;

        Subject::create($validated);
        return redirect(client_route('client.admin.subjects.index'))
            ->with('success', 'Mapel ditambahkan.');
    }

    public function edit(string $id)
    {
        $subject = Subject::withoutGlobalScopes()->findOrFail($id);
        return view('admin.subjects.edit', compact('subject'));
    }

    public function update(Request $request, string $id)
    {
        $subject = Subject::withoutGlobalScopes()->findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
        ]);
        $subject->update($validated);
        return redirect(client_route('client.admin.subjects.index'))
            ->with('success', 'Mapel diperbarui.');
    }

    public function destroy(string $id)
    {
        Subject::withoutGlobalScopes()->findOrFail($id)->delete();
        return redirect(client_route('client.admin.subjects.index'))
            ->with('success', 'Mapel dihapus.');
    }
}