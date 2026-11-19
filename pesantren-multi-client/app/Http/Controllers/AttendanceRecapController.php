<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolClass;
use App\Models\AcademicAttendance;
use Illuminate\Support\Facades\DB;

class AttendanceRecapController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::orderBy('name')->get();

        $query = AcademicAttendance::with(['student.classRoom'])
            ->select('student_id', 'status', DB::raw('count(*) as total'))
            ->groupBy('student_id', 'status');

        if ($request->filled('class_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        $recaps = [];
        foreach ($query->get() as $row) {
            if (!isset($recaps[$row->student_id])) {
                $recaps[$row->student_id] = [
                    'student' => $row->student,
                    'hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpa' => 0,
                ];
            }
            $recaps[$row->student_id][$row->status] = (int) $row->total;
        }

        return view('client.admin.attendance-recap', compact('recaps', 'classes'));
    }
}