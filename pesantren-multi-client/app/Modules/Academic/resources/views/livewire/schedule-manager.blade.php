<?php

use App\Models\Schedule;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\User;
use Livewire\Component;

new class extends Component
{
    public $isModalOpen = false;
    
    // Form Fields
    public $class_id, $subject_id, $teacher_id, $day = 'Senin', $start_time = '08:00', $end_time = '10:00';

    public function render()
    {
        $schedules = Schedule::with(['classes', 'subjects', 'teacher'])->latest()->get();
        $classes = Classes::all();
        $subjects = Subject::all();
        // Ambil user dengan role guru (jika ada), atau ambil semua user untuk testing
        $teachers = User::where('role', 'guru')->orWhere('role', 'admin')->get(); 

        return view('academic::livewire.schedule-manager', compact('schedules', 'classes', 'subjects', 'teachers'));
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInputFields()
    {
        $this->class_id = '';
        $this->subject_id = '';
        $this->teacher_id = '';
        $this->day = 'Senin';
        $this->start_time = '08:00';
        $this->end_time = '10:00';
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate([
            'class_id' => 'required',
            'subject_id' => 'required',
            'teacher_id' => 'required',
            'day' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        Schedule::create([
            'class_id' => $this->class_id,
            'subject_id' => $this->subject_id,
            'teacher_id' => $this->teacher_id,
            'day' => $this->day,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ]);

        session()->flash('message', 'Jadwal berhasil ditambahkan.');
        $this->closeModal();
        $this->resetInputFields();
    }
};
?>

<div>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Jadwal Pelajaran</h2>
            <p class="text-sm text-gray-500 mt-1">Atur jadwal kelas dan guru pengampu.</p>
        </div>
        <button wire:click="create" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Jadwal
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Hari</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Waktu</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Kelas</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Mata Pelajaran</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Guru</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($schedules as $schedule)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $schedule->day }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $schedule->classes->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $schedule->subjects->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $schedule->teacher->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ url('/client/demo/admin/attendance/' . $schedule->id) }}" class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1.5 rounded-md text-xs font-bold transition-colors">Isi Absensi</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada jadwal pelajaran.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Form -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md overflow-hidden">
            <div class="bg-emerald-600 px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg font-bold text-white">Tambah Jadwal Pelajaran</h3>
                <button wire:click="closeModal" class="text-white hover:text-gray-200">&times;</button>
            </div>
            <form wire:submit.prevent="store" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                        <select wire:model="day" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $d)
                                <option value="{{ $d }}">{{ $d }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                            <input type="time" wire:model="start_time" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                            <input type="time" wire:model="end_time" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <select wire:model="class_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Pilih Kelas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('class_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                        <select wire:model="subject_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Pilih Mapel</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                        @error('subject_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Guru Pengampu</label>
                        <select wire:model="teacher_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Pilih Guru</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                        @error('teacher_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 shadow-sm">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>