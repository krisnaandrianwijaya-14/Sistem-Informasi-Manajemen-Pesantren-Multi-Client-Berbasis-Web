<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\User;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['classes', 'subjects', 'teacher'])->latest()->get();
        return view('client.admin.schedules', compact('schedules'));
    }

    public function create()
    {
        $classes = Classes::all();
        $subjects = Subject::all();
        $teachers = User::where('role', 'guru')->orWhere('role', 'admin')->get(); 
        return view('client.admin.schedules-create', compact('classes', 'subjects', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'subject_id' => 'required',
            'teacher_id' => 'required',
            'day' => 'required',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        // VALIDASI BENTROK JADWAL
        $conflict = Schedule::where('tenant_id', 1)
            ->where('day', $request->day)
            ->where(function ($query) use ($request) {
                $query->where('class_id', $request->class_id)
                      ->orWhere('teacher_id', $request->teacher_id);
            })
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
            })->exists();

        if ($conflict) {
            return back()->withErrors(['error' => 'Jadwal Bentrok! Guru atau Kelas tersebut sudah memiliki jadwal di jam ini.'])->withInput();
        }

        Schedule::create([
            'tenant_id' => 1,
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect('/client/demo/admin/schedules')->with('success', 'Jadwal berhasil ditambahkan.');
    }
}