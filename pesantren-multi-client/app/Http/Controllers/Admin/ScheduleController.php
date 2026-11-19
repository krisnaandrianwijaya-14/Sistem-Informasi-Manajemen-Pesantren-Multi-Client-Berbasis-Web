<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\SchoolClass;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['classRoom', 'subject', 'teacher'])
            ->orderBy('day')->orderBy('start_time')->get();
        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();
        return view('admin.schedules.create', compact('classes', 'subjects', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        DB::transaction(function () use ($validated) {
            $start = $validated['start_time'];
            $end = $validated['end_time'];

            $classConflict = Schedule::where('class_id', $validated['class_id'])
                ->where('day', $validated['day'])
                ->where('start_time', '<', $end)
                ->where('end_time', '>', $start)
                ->lockForUpdate()->exists();

            if ($classConflict) {
                throw ValidationException::withMessages([
                    'class_id' => 'Kelas ini sudah memiliki jadwal pada jam tersebut.',
                ]);
            }

            $teacherConflict = Schedule::where('teacher_id', $validated['teacher_id'])
                ->where('day', $validated['day'])
                ->where('start_time', '<', $end)
                ->where('end_time', '>', $start)
                ->lockForUpdate()->exists();

            if ($teacherConflict) {
                throw ValidationException::withMessages([
                    'teacher_id' => 'Guru ini sudah mengajar pada jam tersebut.',
                ]);
            }

            Schedule::create($validated);
        });

        return redirect(client_route('client.admin.schedules.index'))
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function destroy(string $id)
    {
        Schedule::findOrFail($id)->delete();
        return redirect(client_route('client.admin.schedules.index'))
            ->with('success', 'Jadwal dihapus.');
    }
}