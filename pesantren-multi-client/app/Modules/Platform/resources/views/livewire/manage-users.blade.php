<?php

use Livewire\Component;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Hash;

new class extends Component
{
    // Properti untuk Modal
    public bool $showModal = false;

    // Properti Form
    public $name = '';
    public $email = '';
    public $role = 'platform_admin';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'role' => 'required|in:platform_admin,finance_admin,support_specialist,saas_auditor',
    ];

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['name', 'email', 'role']);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function save()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make('password'), // Default password
            'role' => $this->role,
            'email_verified_at' => now(), // Langsung terverifikasi
        ]);

        // Catat ke Audit Log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'create_platform_user',
            'description' => 'Menambahkan pengguna platform baru: ' . $this->name . ' (' . $this->role . ')',
            'ip_address' => request()->ip(),
        ]);

        session()->flash('message', 'Pengguna platform berhasil ditambahkan!');
        $this->closeModal();
    }

    public function render()
    {
        return view('platform::livewire.manage-users', [
            'users' => User::whereIn('role', ['platform_admin', 'finance_admin', 'support_specialist', 'saas_auditor'])->latest()->get()
        ]);
    }
};
?>

<!-- PEMBUNGKUS UTAMA (HANYA SATU) -->
<div class="max-w-6xl mx-auto relative min-h-[80vh]">

    @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm" role="alert">
            <p class="font-bold">Berhasil</p>
            <p>{{ session('message') }}</p>
        </div>
    @endif

    <!-- Tabel Daftar Pengguna (Di Atas) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Administrator & Operator SiSantri</h2>
                <p class="text-sm text-gray-500">Daftar pengguna dengan otoritas global pada lingkup platform.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                        <th class="px-6 py-4 font-semibold">Nama</th>
                        <th class="px-6 py-4 font-semibold">Email</th>
                        <th class="px-6 py-4 font-semibold">Role</th>
                        <th class="px-6 py-4 font-semibold text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-bold text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 capitalize">{{ str_replace('_', ' ', $user->role) }}</td>
                            <td class="px-6 py-4 text-right">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">Aktif</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-400">Belum ada pengguna terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 text-sm text-gray-500 bg-gray-50">
            Menampilkan {{ $users->count() }} Pengguna terdaftar
        </div>
    </div>

    <!-- Tombol Melayang (HANYA MUNCUL UNTUK SUPER ADMIN) -->
    @if(auth()->user()->role === 'platform_admin')
    <button wire:click="openModal" class="fixed bottom-10 right-10 bg-[#065f46] text-white px-6 py-4 rounded-full shadow-2xl hover:bg-[#047857] transition transform hover:scale-105 flex items-center gap-2 font-bold z-40">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Pengguna
    </button>
    @endif

    <!-- Modal Form Tambah Pengguna -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                <form wire:submit="save">
                    <div class="bg-white px-6 py-6 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900" id="modal-title">Tambah Pengguna Platform</h3>
                    </div>

                    <div class="bg-white px-6 py-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap *</label>
                            <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#065f46] focus:ring-[#065f46]">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alamat E-mail *</label>
                            <input type="email" wire:model="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#065f46] focus:ring-[#065f46]">
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Role / Hak Akses *</label>
                            <select wire:model="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#065f46] focus:ring-[#065f46]">
                                <option value="platform_admin">Super Admin</option>
                                <option value="finance_admin">Finance Admin</option>
                                <option value="support_specialist">Support Specialist</option>
                                <option value="saas_auditor">SaaS Auditor</option>
                            </select>
                        </div>
                    </div>

                    <!-- Footer Modal -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                        <button type="button" wire:click="closeModal" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-100 transition">
                            Batalkan
                        </button>
                        <button type="submit" class="bg-[#065f46] text-white px-6 py-2 rounded-lg font-medium hover:bg-[#047857] transition shadow-sm flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

</div>
