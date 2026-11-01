<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\SchoolClass;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['schoolClass', 'subject', 'teacher'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

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
            'day_of_week' => 'required|integer|between:1,7',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $this->checkConflict($validated);

        Schedule::create($validated);

        return redirect()->route('client.admin.schedules.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    private function checkConflict(array $data): void
    {
        $start = $data['start_time'];
        $end = $data['end_time'];

        $classConflict = Schedule::where('class_id', $data['class_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start)
            ->exists();

        if ($classConflict) {
            throw ValidationException::withMessages([
                'class_id' => 'Kelas ini sudah memiliki jadwal pada jam tersebut.',
            ]);
        }

        $teacherConflict = Schedule::where('teacher_id', $data['teacher_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start)
            ->exists();

        if ($teacherConflict) {
            throw ValidationException::withMessages([
                'teacher_id' => 'Guru ini sudah mengajar pada jam tersebut.',
            ]);
        }
    }
}
