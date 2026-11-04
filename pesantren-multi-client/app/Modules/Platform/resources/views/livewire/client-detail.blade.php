<?php

use Livewire\Component;
use App\Models\Client;

new class extends Component
{
    public Client $client;
    public array $modules = [];

    // Daftar modul yang tersedia di platform
    public array $available_modules = [
        'kurikulum' => 'Kurikulum & Madin',
        'profil' => 'Profil & Data Santri',
        'keuangan' => 'Keuangan & Syahriah',
        'wali' => 'Portal Wali Santri',
    ];

    public function mount(Client $client)
    {
        $this->client = $client;
        // Ambil modul yang sedang aktif dari database (jika kosong, jadikan array kosong)
        $this->modules = $client->active_modules ?? [];
    }

    public function saveModules()
    {
        // Simpan pilihan modul ke database
        $this->client->update([
            'active_modules' => $this->modules
        ]);

        session()->flash('message', 'Konfigurasi modul berhasil diperbarui!');
    }

    public function render()
    {
        return view('platform::livewire.client-detail');
    }
};
?>

<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header Profil -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-xl bg-[#065f46] text-white flex items-center justify-center text-2xl font-bold">
                {{ substr($client->name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $client->name }}</h1>
                <p class="text-sm text-gray-500">
                    ID Tenant: #{{ str_pad($client->id, 4, '0', STR_PAD_LEFT) }} •
                    Terdaftar sejak: {{ $client->created_at->format('d M Y') }}
                </p>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.clients.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 border border-gray-300 px-4 py-2 rounded-lg transition">
                &larr; Kembali
            </a>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4" role="alert">
            <p class="font-bold">Berhasil</p>
            <p>{{ session('message') }}</p>
        </div>
    @endif

    <!-- Konfigurasi Modul -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Konfigurasi Lisensi Modul</h2>
            <p class="text-sm text-gray-500">Aktifkan atau nonaktifkan modul pesantren secara modular sesuai paket langganan mereka.</p>
        </div>

        <form wire:submit="saveModules" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($available_modules as $key => $label)
                <label class="flex items-start gap-3 p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                    <input type="checkbox" wire:model="modules" value="{{ $key }}" class="mt-1 w-5 h-5 rounded border-gray-300 text-[#065f46] focus:ring-[#065f46]">
                    <div>
                        <span class="block text-base font-semibold text-gray-800">{{ $label }}</span>
                        <span class="block text-xs text-gray-500 mt-1">Modul ini akan aktif di dashboard pesantren.</span>
                    </div>
                </label>
                @endforeach
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-[#065f46] text-white px-6 py-2 rounded-lg font-medium hover:bg-[#047857] transition shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Konfigurasi Modul
                </button>
            </div>
        </form>
    </div>

</div>
