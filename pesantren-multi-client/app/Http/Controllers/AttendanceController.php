<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\AcademicAttendance;

class AttendanceController extends Controller
{
    public function create($scheduleId)
    {
        $schedule = Schedule::with(['classRoom', 'subject', 'teacher'])->findOrFail($scheduleId);

        $students = Student::where('class_id', $schedule->class_id)
            ->where('status', 'active')
            ->orderBy('full_name')
            ->get();

        $date = request('date', now()->format('Y-m-d'));

        $existingAttendances = AcademicAttendance::where('schedule_id', $scheduleId)
            ->where('date', $date)
            ->get()->keyBy('student_id');

        return view('client.admin.attendance', compact('schedule', 'students', 'date', 'existingAttendances'));
    }

    public function store(Request $request, $scheduleId)
    {
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.status' => 'required|in:hadir,sakit,izin,alpa',
        ]);

        $schedule = Schedule::findOrFail($scheduleId);

        foreach ($request->attendance as $studentId => $data) {
            AcademicAttendance::updateOrCreate(
                [
                    'schedule_id' => $scheduleId,
                    'student_id' => $studentId,
                    'date' => $request->date,
                ],
                [
                    'status' => $data['status'],
                    'note' => $data['note'] ?? null,
                ]
            );
        }

        return redirect()->route('client.admin.attendance', $scheduleId)
            ->with('success', 'Absensi tersimpan (' . now()->format('H:i') . ' WIB).');
    }
}