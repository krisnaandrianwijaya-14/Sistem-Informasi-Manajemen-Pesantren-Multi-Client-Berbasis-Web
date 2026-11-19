<?php

use App\Models\Schedule;
use App\Models\Student;
use App\Models\AcademicAttendance;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public $scheduleId;
    public $schedule;
    public $date;
    public $students = [];
    public $attendanceData = [];

    public function mount($scheduleId)
    {
        $this->scheduleId = $scheduleId;
        $this->date = now()->format('Y-m-d');
        
        // Ambil jadwal beserta relasinya
        $this->schedule = Schedule::with(['classes', 'subjects', 'teacher'])->findOrFail($scheduleId);
        
        // (Opsional) Pastikan hanya guru yang bersangkutan yang bisa akses
        // if ($this->schedule->teacher_id !== Auth::id()) {
        //     abort(403);
        // }

        $this->loadStudents();
    }

    public function loadStudents()
    {
        // Ambil santri aktif di tenant ini (otomatis terfilter oleh Global Scope Tenant)
        $this->students = Student::where('status', 'active')->get();

        foreach ($this->students as $student) {
            $existing = AcademicAttendance::where('schedule_id', $this->scheduleId)
                ->where('student_id', $student->id)
                ->where('date', $this->date)
                ->first();

            $this->attendanceData[$student->id] = [
                'status' => $existing ? $existing->status : 'hadir',
                'note' => $existing ? $existing->note : '',
            ];
        }
    }

    public function markAllPresent()
    {
        foreach ($this->attendanceData as $id => $data) {
            $this->attendanceData[$id]['status'] = 'hadir';
        }
    }

    public function save()
    {
        $this->validate([
            'attendanceData.*.status' => 'required|in:hadir,izin,sakit,alpa',
        ]);

        foreach ($this->attendanceData as $studentId => $data) {
            AcademicAttendance::updateOrCreate(
                [
                    'schedule_id' => $this->scheduleId,
                    'student_id' => $studentId,
                    'date' => $this->date,
                ],
                [
                    'status' => $data['status'],
                    'note' => $data['note'],
                ]
            );
        }

        session()->flash('status', 'Presensi berhasil disimpan pada ' . now()->format('H:i') . ' WIB.');
    }

    public function render()
    {
        return view('academic::livewire.teacher-attendance', [
            'students' => $this->students
        ]);
    }
};
?>

<div class="max-w-6xl mx-auto p-4 bg-white">
    <!-- Header (Sesuai Figma) -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Presensi Harian Santri</h1>
            <div class="flex flex-wrap gap-4 text-sm text-gray-500 mt-1">
                <p>Mata Pelajaran: <span class="font-semibold text-gray-800">{{ $schedule->subjects->name ?? '-' }}</span></p>
                <p>Kelas: <span class="font-semibold text-gray-800">{{ $schedule->classes->name ?? '-' }}</span></p>
                <p>Tanggal: <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}</span></p>
            </div>
        </div>
        <div class="flex gap-2">
            <button wire:click="markAllPresent" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded shadow-sm hover:bg-gray-50">
                Tandai Semua Hadir
            </button>
            <button wire:click="save" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded shadow-sm hover:bg-green-700">
                Simpan Presensi
            </button>
        </div>
    </div>

    <!-- Alert Sukses -->
    @if (session()->has('status'))
        <div class="p-3 mb-4 text-sm text-green-800 bg-green-100 border border-green-200 rounded">
            Status Presensi: {{ session('status') }}
        </div>
    @endif

    <!-- Tabel (Sesuai Figma) -->
    <div class="overflow-x-auto border border-gray-200 rounded-lg">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead class="bg-gray-50">
                <tr class="text-xs font-semibold tracking-wider text-gray-500 uppercase border-b border-gray-200">
                    <th class="px-6 py-3">Foto</th>
                    <th class="px-6 py-3">NIS</th>
                    <th class="px-6 py-3">Nama Lengkap</th>
                    <th class="px-6 py-3 text-center">Status Kehadiran</th>
                    <th class="px-6 py-3">Catatan Guru</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($students as $student)
                    <tr wire:key="student-{{ $student->id }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center w-10 h-10 text-sm font-bold text-gray-500 bg-gray-200 rounded-full">
                                {{ substr($student->full_name ?? 'A', 0, 1) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $student->nis ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $student->full_name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                @foreach(['hadir' => 'Hadir', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alpa' => 'Alpa'] as $val => $label)
                                    <label class="cursor-pointer">
                                        <input type="radio" wire:model="attendanceData.{{ $student->id }}.status" value="{{ $val }}" class="sr-only peer">
                                        <span class="px-3 py-1 text-xs font-semibold border rounded-full peer-checked:bg-green-600 peer-checked:text-white peer-checked:border-green-600 hover:bg-gray-100 peer-checked:hover:bg-green-700">
                                            {{ $label }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <input type="text" 
                                   wire:model="attendanceData.{{ $student->id }}.note" 
                                   placeholder="Tidak ada keterangan"
                                   class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data santri di pesantren ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>