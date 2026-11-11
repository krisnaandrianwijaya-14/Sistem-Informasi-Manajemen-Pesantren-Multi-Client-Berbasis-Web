<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\AcademicAttendance;
use Illuminate\Support\Facades\DB;

class AttendanceRecapController extends Controller
{
    public function index(Request $request)
    {
        $classes = \App\Models\Classes::all();
        
        $query = AcademicAttendance::with(['student.classes'])
            ->select('student_id', 'status', DB::raw('count(*) as total'))
            ->groupBy('student_id', 'status');

        if ($request->has('class_id') && $request->class_id != '') {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        $rawData = $query->get();

        $recaps = [];
        foreach ($rawData as $row) {
            if (!isset($recaps[$row->student_id])) {
                $recaps[$row->student_id] = [
                    'student' => $row->student,
                    'hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpa' => 0,
                ];
            }
            $recaps[$row->student_id][$row->status] = $row->total;
        }

        return view('client.admin.attendance-recap', compact('recaps', 'classes'));
    }
}