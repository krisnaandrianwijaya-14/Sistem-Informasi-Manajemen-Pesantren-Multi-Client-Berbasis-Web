<?php

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use App\Models\Client;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

new class extends Component
{
    public bool $showModal = false;
    public $name = '';
    public $slug = '';
    public $address = '';
    public $city = '';
    public $estimated_students = '';
    public $admin_name = '';
    public $admin_email = '';
    public $modules = [];

    public $available_modules = [
        'kurikulum' => 'Kurikulum & Madin',
        'profil' => 'Profil & Data Santri',
        'keuangan' => 'Keuangan & Syahriah',
        'wali' => 'Portal Wali Santri',
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:clients,slug',
        'admin_name' => 'required|string|max:255',
        'admin_email' => 'required|email|unique:users,email',
    ];

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['name', 'slug', 'address', 'city', 'estimated_students', 'admin_name', 'admin_email', 'modules']);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function save()
    {
        $this->validate();

        $client = Client::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'address' => $this->address,
            'city' => $this->city,
            'estimated_students' => $this->estimated_students,
            'active_modules' => $this->modules,
            'status' => 'active',
        ]);

        $admin = User::create([
            'name' => $this->admin_name,
            'email' => $this->admin_email,
            'password' => Hash::make(Str::random(32)),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        $admin->clients()->attach($client->id, ['role' => 'admin_pesantren']);
         Password::broker()->sendResetLink(['email' => $admin->email]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'create_tenant',
            'description' => 'Mendaftarkan pesantren baru: ' . $client->name,
            'ip_address' => request()->ip(),
        ]);

        session()->flash('message', 'Pesantren berhasil didaftarkan!');
        $this->closeModal();
    }

    public function render()
    {
        return view('platform::livewire.manage-clients', [
            'clients' => Client::with(['users' => function($query) {
                $query->wherePivot('role', 'admin_pesantren');
            }])->withCount('users')->latest()->get()
        ]);
    }
};
?>

<!-- PEMBUNGKUS UTAMA (WAJIB HANYA SATU) -->
<div class="max-w-6xl mx-auto relative min-h-[80vh]">

    @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm" role="alert">
            <p class="font-bold">Berhasil</p>
            <p>{{ session('message') }}</p>
        </div>
    @endif

    <!-- Tabel Daftar Pesantren -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Database Tenant Pesantren</h2>
                <p class="text-sm text-gray-500">Daftar seluruh pesantren yang terdaftar di platform SiSantri.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                        <th class="px-6 py-4 font-semibold">Nama Pesantren</th>
                        <th class="px-6 py-4 font-semibold">Lokasi</th>
                        <th class="px-6 py-4 font-semibold">Jumlah Santri</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($clients as $client)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $client->name }}</div>
                                <div class="text-xs text-gray-500">/{{ $client->slug }}</div>
                                <div class="text-xs text-blue-600 mt-1 font-mono">
                                    Admin: {{ $client->users->first()?->email ?? 'Tidak ada' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $client->city ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $client->estimated_students ?? 0 }} Santri</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700 uppercase">
                                    {{ $client->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if(auth()->user()->role === 'platform_admin')
                                    <a href="{{ route('admin.clients.show', $client) }}" class="text-sm font-semibold text-[#065f46] hover:underline">Detail Modul</a>
                                @else
                                    <span class="text-xs text-gray-400 italic">Hanya Lihat</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400">Belum ada pesantren yang didaftarkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 text-sm text-gray-500 bg-gray-50">
            Menampilkan {{ $clients->count() }} Pesantren terdaftar
        </div>
    </div>

    <!-- Tombol Melayang (HANYA MUNCUL UNTUK SUPER ADMIN) -->
    @if(auth()->user()->role === 'platform_admin')
    <button wire:click="openModal" class="fixed bottom-10 right-10 bg-[#065f46] text-white px-6 py-4 rounded-full shadow-2xl hover:bg-[#047857] transition transform hover:scale-105 flex items-center gap-2 font-bold z-40">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Pesantren
    </button>
    @endif

    <!-- Modal Form Tambah Pesantren -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">

                <form wire:submit="save">
                    <div class="bg-white px-6 py-6 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900" id="modal-title">Daftarkan Pesantren Baru</h3>
                    </div>

                    <div class="bg-white px-6 py-6 max-h-[65vh] overflow-y-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- KIRI -->
                            <div class="space-y-4">
                                <h4 class="font-semibold text-sm text-[#065f46] uppercase tracking-wide border-b pb-2">1. Informasi Profil Lembaga</h4>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Pondok Pesantren *</label>
                                    <input type="text" wire:model.live="name" placeholder="e.g., Pesantren Al-Hikmah" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#065f46] focus:ring-[#065f46]">
                                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Slug URL *</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">localhost:8000/</span>
                                        <input type="text" wire:model="slug" class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 focus:border-[#065f46] focus:ring-[#065f46]">
                                    </div>
                                    @error('slug') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                                    <textarea wire:model="address" rows="2" placeholder="e.g., Jl. Raya Gayungsari No. 40" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#065f46] focus:ring-[#065f46]"></textarea>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Provinsi / Kota</label>
                                        <input type="text" wire:model="city" placeholder="e.g., Surabaya" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#065f46] focus:ring-[#065f46]">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Estimasi Santri</label>
                                        <input type="number" wire:model="estimated_students" placeholder="e.g., 500" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#065f46] focus:ring-[#065f46]">
                                    </div>
                                </div>
                            </div>

                            <!-- KANAN -->
                            <div class="space-y-4">
                                <h4 class="font-semibold text-sm text-[#065f46] uppercase tracking-wide border-b pb-2">2. Kredensial Administrator</h4>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap Admin *</label>
                                    <input type="text" wire:model="admin_name" placeholder="e.g., Ust. Khoirul Anam" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#065f46] focus:ring-[#065f46]">
                                    @error('admin_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Alamat E-mail Aktif *</label>
                                    <input type="email" wire:model="admin_email" placeholder="e.g., anam@alhikmah.id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#065f46] focus:ring-[#065f46]">
                                    @error('admin_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <h4 class="font-semibold text-sm text-[#065f46] uppercase tracking-wide border-b pb-2 mt-6">3. Konfigurasi Awal Modul</h4>
                                <div class="space-y-2 mt-2">
                                    @foreach($available_modules as $key => $label)
                                    <label class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                        <input type="checkbox" wire:model="modules" value="{{ $key }}" class="mt-1 rounded border-gray-300 text-[#065f46] focus:ring-[#065f46]">
                                        <span class="block text-sm font-medium text-gray-800">{{ $label }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Modal -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                        <button type="button" wire:click="closeModal" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-100 transition">
                            Batalkan
                        </button>
                        <button type="submit" class="bg-[#065f46] text-white px-6 py-2 rounded-lg font-medium hover:bg-[#047857] transition shadow-sm flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan & Deploy
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

</div>
<!-- AKHIR PEMBUNGKUS UTAMA -->
