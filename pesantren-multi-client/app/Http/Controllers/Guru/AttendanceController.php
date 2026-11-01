<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\AcademicAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        // Cari data guru berdasarkan user yang login
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        
        // Ambil jadwal mengajar guru ini
        $schedules = Schedule::with(['schoolClass', 'subject'])
            ->where('teacher_id', $teacher->id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('guru.schedules.index', compact('schedules'));
    }

    public function create($schedule_id)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        $schedule = Schedule::with(['schoolClass', 'subject'])->findOrFail($schedule_id);

        // Keamanan: Pastikan guru hanya bisa mengisi absensi kelasnya sendiri
        if ($schedule->teacher_id !== $teacher->id) {
            abort(403, 'Anda tidak berhak mengisi absensi kelas ini.');
        }

        $date = request('date', now()->toDateString());
        $students = Student::where('class_id', $schedule->class_id)->orderBy('name')->get();
        
        // Cek apakah absensi hari ini sudah pernah diisi
        $existingAttendances = AcademicAttendance::where('schedule_id', $schedule->id)
            ->where('date', $date)
            ->get()->keyBy('student_id');

        return view('guru.attendances.form', compact('schedule', 'students', 'date', 'existingAttendances'));
    }

    public function store(Request $request, $schedule_id)
    {
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:hadir,sakit,izin,alpa',
        ]);

        $schedule = Schedule::findOrFail($schedule_id);
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        if ($schedule->teacher_id !== $teacher->id) {
            abort(403);
        }

        foreach ($request->attendance as $student_id => $status) {
            // Gunakan updateOrCreate untuk mencegah error jika data sudah ada (Unique Constraint)
            AcademicAttendance::updateOrCreate(
                [
                    'schedule_id' => $schedule->id,
                    'student_id' => $student_id,
                    'date' => $request->date,
                ],
                [
                    'status' => $status,
                    'note' => null,
                ]
            );
        }

        return redirect()->route('client.guru.attendances.index')
            ->with('success', 'Absensi berhasil disimpan untuk tanggal ' . $request->date);
    }
}
