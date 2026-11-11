<?php

use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $students = Student::where('full_name', 'like', '%' . $this->search . '%')
            ->orWhere('nis', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('academic::livewire.student-manager', compact('students'));
    }
    
    public function delete($id)
    {
        // Soft delete (hanya menandai terhapus, tidak menghapus permanen)
        Student::find($id)->delete();
        session()->flash('message', 'Data Santri berhasil dihapus (Soft Delete).');
    }
};
?>

<div>
    <!-- Header & Tombol Tambah -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Database Master Santri</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola seluruh data santri terdaftar di pesantren.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ url('/client/demo/admin/students/create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Santri
            </a>
        </div>
    </div>

    <!-- Alert Sukses -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Bar Pencarian -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500" placeholder="Cari nama atau NIS...">
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Foto</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">NIS</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Nama Lengkap</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Gender</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($students as $student)
                <tr class="hover:bg-gray-50" wire:key="student-{{ $student->id }}">
                    <td class="px-6 py-4">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-sm font-bold text-emerald-700 uppercase">
                            {{ substr($student->full_name, 0, 2) }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $student->nis }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $student->full_name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 text-xs font-semibold {{ $student->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-full">
                            {{ $student->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <!-- Aksi: Detail & Hapus -->
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ url('/client/demo/admin/students/' . $student->id) }}" class="text-emerald-600 hover:text-emerald-800 text-sm font-semibold underline decoration-2 underline-offset-2">Detail</a>
                            
                            <form action="{{ url('/client/demo/admin/students/' . $student->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus santri ini?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus" class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">Data santri tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $students->links() }}
        </div>
    </div>
</div>