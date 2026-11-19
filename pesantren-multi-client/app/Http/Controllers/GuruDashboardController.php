<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class GuruDashboardController extends Controller
{
    public function index()
    {
        // Ambil jadwal HANYA untuk guru yang sedang login
        // Jika Auth::id() kosong (karena belum login), kita fallback ke ID 1 untuk testing
        $teacherId = Auth::id() ?? 1; 
        
        $schedules = Schedule::with(['classes', 'subjects'])
            ->where('teacher_id', $teacherId)
            ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('start_time')
            ->get();

        return view('client.guru.dashboard', compact('schedules'));
    }
}