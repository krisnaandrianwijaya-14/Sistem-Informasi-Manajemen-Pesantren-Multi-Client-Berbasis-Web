<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        // Ambil data untuk dropdown di form
        $classes = ClassRoom::all();
        $subjects = Subject::all();
        
        // Mengambil santri (Sesuaikan 'role' dengan yang ada di database Anda, misal: 'santri' atau 'student')
        $students = User::where('role', 'santri')->get(); 

        return view('academic.attendance.index', compact('classes', 'subjects', 'students'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'class_room_id' => 'required',
            'subject_id' => 'required',
            'date' => 'required|date',
            'status' => 'required|array', // array of student_id => status
        ]);

        // Looping data dari form dan simpan ke database
        foreach ($request->status as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId, // Sesuaikan dengan nama kolom di tabel Anda
                    'class_room_id' => $request->class_room_id,
                    'subject_id' => $request->subject_id,
                    'date' => $request->date,
                ],
                [
                    'status' => $status,
                    'notes' => $request->notes[$studentId] ?? null,
                    'recorded_by' => Auth::id(), // Mencatat siapa guru yang input
                ]
            );
        }

        return back()->with('success', 'Presensi harian berhasil disimpan!');
    }
}
<x-app-layout>
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-6">Presensi Harian Santri</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('akademik.attendance.store') }}" method="POST">
            @csrf
            
            <!-- Panel Filter (Sesuai Mockup) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 bg-white p-4 rounded shadow">
                <div>
                    <label class="block text-sm font-medium text-gray-700">MATA PELAJARAN</label>
                    <select name="subject_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">KELAS & JENJANG</label>
                    <select name="class_room_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">TANGGAL SESI</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border">
                </div>
            </div>

            <div class="flex justify-between items-center mb-4">
                <button type="button" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 font-semibold text-sm">
                    Tandai Semua Hadir
                </button>
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded hover:bg-green-800 font-bold">
                    Simpan Presensi
                </button>
            </div>

            <!-- Tabel Daftar Santri -->
            <div class="bg-white rounded shadow overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Lengkap</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Kehadiran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan Guru</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($students as $student)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $student->nis ?? $student->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $student->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap space-x-3 text-sm">
                                @foreach(['Hadir', 'Izin', 'Sakit', 'Alpa'] as $status)
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="status[{{ $student->id }}]" value="{{ $status }}" class="form-radio text-green-600" {{ $status == 'Hadir' ? 'checked' : '' }}>
                                    <span class="ml-1">{{ $status }}</span>
                                </label>
                                @endforeach
                            </td>
                            <td class="px-6 py-4">
                                <input type="text" name="notes[{{ $student->id }}]" placeholder="Opsional..." class="w-full rounded-md border-gray-300 shadow-sm text-sm p-1 border">
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada data santri.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</x-app-layout>