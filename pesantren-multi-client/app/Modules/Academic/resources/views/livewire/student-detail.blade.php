<?php

use App\Models\Student;
use Livewire\Component;

new class extends Component
{
    public $student;
    public $activeTab = 'profil';

    public function mount($id)
    {
        $this->student = Student::with(['classes'])->findOrFail($id);
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('academic::livewire.student-detail');
    }
};
?>

<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header Profil -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center text-2xl font-bold text-emerald-700">
                {{ substr($student->full_name, 0, 2) }}
            </div>
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl font-bold text-gray-800">{{ $student->full_name }}</h2>
                    <span class="px-2.5 py-1 text-xs font-semibold {{ $student->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-full">
                        {{ $student->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                <p class="text-sm text-gray-500 mt-1">NIS: {{ $student->nis }} • {{ $student->classes->name ?? 'Belum ada kelas' }} • {{ $student->dormitory_name ?? '-' }} {{ $student->room_number ?? '' }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ url('/client/demo/admin/students') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition-colors">&larr; Kembali</a>
            <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-50 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Biodata
            </button>
        </div>
    </div>

    <!-- Tab Navigasi -->
    <div class="border-b border-gray-200">
        <nav class="flex space-x-8" aria-label="Tabs">
            @foreach(['profil' => 'Profil', 'akademik' => 'Akademik', 'absensi' => 'Absensi', 'asrama' => 'Asrama', 'perizinan' => 'Perizinan', 'keuangan' => 'Keuangan'] as $key => $label)
                <button wire:click="setTab('{{ $key }}')" class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === $key ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    {{ $label }}
                </button>
            @endforeach
        </nav>
    </div>

    <!-- Konten Berdasarkan Tab -->
    @if($activeTab === 'profil')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Informasi Pribadi & Keluarga -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Informasi Pribadi & Keluarga</h3>
            <dl class="space-y-4 text-sm">
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-gray-500">Tempat, Tanggal Lahir</dt>
                    <dd class="col-span-2 font-medium text-gray-800">{{ $student->birth_place ?? '-' }}, {{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->translatedFormat('d F Y') : '-' }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-gray-500">Alamat</dt>
                    <dd class="col-span-2 font-medium text-gray-800">{{ $student->address ?? '-' }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-gray-500">Nama Ayah</dt>
                    <dd class="col-span-2 font-medium text-gray-800">{{ $student->father_name ?? '-' }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-gray-500">Nama Ibu</dt>
                    <dd class="col-span-2 font-medium text-gray-800">{{ $student->mother_name ?? '-' }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-gray-500">Kontak Orang Tua</dt>
                    <dd class="col-span-2 font-medium text-gray-800">{{ $student->phone_number ?? '-' }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-gray-500">Alamat Asal</dt>
                    <dd class="col-span-2 font-medium text-gray-800">{{ $student->address ?? '-' }}</dd>
                </div>
            </dl>
        </div>

        <!-- Status Keuangan & SPP (Dummy sesuai Figma) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Status Keuangan & SPP</h3>
            <dl class="space-y-4 text-sm">
                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                    <dt class="text-gray-500">SPP/Tahun Masuk 2026</dt>
                    <dd><span class="px-2.5 py-1 text-xs font-bold bg-green-100 text-green-700 rounded-full">LUNAS</span></dd>
                </div>
                <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                    <dt class="text-gray-500">Uang Makan Masuk 2026</dt>
                    <dd><span class="px-2.5 py-1 text-xs font-bold bg-green-100 text-green-700 rounded-full">LUNAS</span></dd>
                </div>
                <div class="flex justify-between items-center">
                    <dt class="text-gray-500">Gedung / Sarpras</dt>
                    <dd><span class="px-2.5 py-1 text-xs font-bold bg-green-100 text-green-700 rounded-full">LUNAS</span></dd>
                </div>
            </dl>
        </div>
    </div>
    @else
    <!-- Placeholder untuk tab lain -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        <h3 class="text-lg font-medium text-gray-900 mb-1">Fitur dalam Pengembangan</h3>
        <p class="text-gray-500">Tab {{ ucfirst($activeTab) }} akan segera tersedia.</p>
    </div>
    @endif
</div>