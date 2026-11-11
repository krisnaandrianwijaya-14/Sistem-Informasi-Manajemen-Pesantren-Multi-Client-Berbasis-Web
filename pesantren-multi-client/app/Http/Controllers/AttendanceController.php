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
        // Ambil data jadwal beserta relasinya
        $schedule = Schedule::with(['classes', 'subjects', 'teacher'])->findOrFail($scheduleId);
        
        // Ambil santri aktif di kelas tersebut
        $students = Student::where('class_id', $schedule->class_id)
            ->where('status', 'active')
            ->orderBy('full_name')
            ->get();
            
        $date = now()->format('Y-m-d');
        
        // Cek apakah absensi hari ini sudah pernah diisi (untuk ditampilkan di form)
        $existingAttendances = AcademicAttendance::where('schedule_id', $scheduleId)
            ->where('date', $date)
            ->get()
            ->keyBy('student_id');

        return view('client.admin.attendance', compact('schedule', 'students', 'date', 'existingAttendances'));
    }

    public function store(Request $request, $scheduleId)
    {
        $schedule = Schedule::findOrFail($scheduleId);
        $date = now()->format('Y-m-d');
        
        // Data absensi dikirim dalam bentuk array: attendance[student_id][status]
        $attendances = $request->input('attendance', []);

        foreach ($attendances as $studentId => $data) {
            AcademicAttendance::updateOrCreate(
                [
                    'schedule_id' => $scheduleId,
                    'student_id' => $studentId,
                    'date' => $date,
                ],
                [
                    'status' => $data['status'],
                    'note' => $data['note'] ?? null,
                    'tenant_id' => 1, // Hardcode sementara untuk testing
                ]
            );
        }

        return redirect('/client/demo/admin/attendance/' . $scheduleId)->with('success', 'Absensi berhasil disimpan pada ' . now()->format('H:i') . ' WIB.');
    }
}