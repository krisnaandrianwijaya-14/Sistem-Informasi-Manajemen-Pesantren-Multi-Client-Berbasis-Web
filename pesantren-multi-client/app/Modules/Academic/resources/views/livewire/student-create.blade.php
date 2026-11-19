<?php

use App\Models\Student;
use App\Models\Classes;
use Livewire\Component;

new class extends Component
{
    // Data Pribadi
    public $full_name, $nis, $gender = 'L', $birth_place, $birth_date;
    
    // Akademik & Asrama
    public $class_id, $dormitory_name, $room_number;
    
    // Orang Tua
    public $father_name, $mother_name, $phone_number, $address;
    
    // Variabel untuk menampung error
    public $errorMessage = null;

    public function render()
    {
        return view('academic::livewire.student-create', [
            'classes' => Classes::all()
        ]);
    }

    public function store()
    {
        $this->errorMessage = null;
        
        $this->validate([
            'full_name' => 'required',
            'nis' => 'required|unique:students,nis',
            'gender' => 'required|in:L,P',
        ]);

        try {
            Student::create([
                'full_name' => $this->full_name,
                'nis' => $this->nis,
                'gender' => $this->gender,
                'birth_place' => $this->birth_place,
                'birth_date' => $this->birth_date ?: null,
                'class_id' => $this->class_id ?: null,
                'dormitory_name' => $this->dormitory_name,
                'room_number' => $this->room_number,
                'father_name' => $this->father_name,
                'mother_name' => $this->mother_name,
                'phone_number' => $this->phone_number,
                'address' => $this->address,
                'status' => 'active',
                'tenant_id' => 1, // PAKSA ISI 1 AGAR TIDAK ERROR FOREIGN KEY
            ]);

            session()->flash('success', 'Data santri berhasil disimpan.');
            return redirect('/client/demo/admin/students');

        } catch (\Exception $e) {
            // TANGKAP ERROR DATABASE DAN TAMPILKAN
            $this->errorMessage = 'Gagal menyimpan ke database: ' . $e->getMessage();
        }
    }
};
?>

<div class="max-w-4xl mx-auto">
    <!-- TAMPILKAN ERROR JIKA ADA -->
    @if ($errorMessage)
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p class="font-bold">Terjadi Kesalahan!</p>
            <p>{{ $errorMessage }}</p>
        </div>
    @endif

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Tambah Santri Baru</h2>
            <p class="text-sm text-gray-500 mt-1">Isi formulir di bawah ini dengan lengkap.</p>
        </div>
        <a href="{{ url('/client/demo/admin/students') }}" class="text-gray-500 hover:text-gray-800 text-sm font-medium">&larr; Kembali</a>
    </div>

    <form wire:submit.prevent="store" class="space-y-6">
        <!-- BAGIAN 1: DATA PRIBADI -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-3 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">1. Data Pribadi</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="full_name" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Nama santri">
                    @error('full_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Induk Siswa (NIS) <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="nis" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Contoh: 20260401">
                    @error('nis') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                    <select wire:model="gender" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                        <input type="text" wire:model="birth_place" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Kota">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                        <input type="date" wire:model="birth_date" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- BAGIAN 2: AKADEMIK & ASRAMA -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-3 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">2. Akademik & Asrama</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kelas Madrasah</label>
                    <select wire:model="class_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Pilih Kelas</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gedung Asrama</label>
                    <input type="text" wire:model="dormitory_name" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Mis: Gedung Al-Ghazali">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Kamar</label>
                    <input type="text" wire:model="room_number" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Mis: K-03">
                </div>
            </div>
        </div>

        <!-- BAGIAN 3: DATA ORANG TUA -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-3 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">3. Data Orang Tua / Wali</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ayah Kandung</label>
                    <input type="text" wire:model="father_name" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ibu Kandung</label>
                    <input type="text" wire:model="mother_name" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Handphone/WhatsApp</label>
                    <input type="text" wire:model="phone_number" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Rumah Lengkap</label>
                    <textarea wire:model="address" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3 pb-8">
            <a href="{{ url('/client/demo/admin/students') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 shadow-sm">Simpan Data Santri</button>
        </div>
    </form>
</div>