<x-layouts.admin title="Dashboard Admin">
    <!-- Header Banner -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Assalamu'alaikum, Admin Al-Hikmah</h2>
            <p class="text-sm text-gray-500 mt-1">Berikut ringkasan statistik harian pesantren Anda. Semoga modul akademik dan syariah diridhoi.</p>
        </div>
        <a href="#" class="mt-4 md:mt-0 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Santri Baru
        </a>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Santri</p>
                    <h3 class="text-3xl font-bold text-gray-800">1,250</h3>
                    <p class="text-xs text-gray-400 mt-1">Anak terdaftar</p>
                </div>
                <div class="p-2.5 bg-emerald-50 rounded-lg text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Ustadz & Guru</p>
                    <h3 class="text-3xl font-bold text-gray-800">42</h3>
                    <p class="text-xs text-gray-400 mt-1">Pengajar aktif</p>
                </div>
                <div class="p-2.5 bg-emerald-50 rounded-lg text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Kehadiran Hari Ini</p>
                    <h3 class="text-3xl font-bold text-gray-800">98.2%</h3>
                    <p class="text-xs text-gray-400 mt-1">Ahmad Fauzan & 1,220 Lainnya</p>
                </div>
                <div class="p-2.5 bg-emerald-50 rounded-lg text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Pembayaran Sya'riah</p>
                    <h3 class="text-3xl font-bold text-gray-800">82%</h3>
                    <p class="text-xs text-gray-400 mt-1">Lunas Bulan Ini</p>
                </div>
                <div class="p-2.5 bg-emerald-50 rounded-lg text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-sm font-bold text-gray-800 mb-6">Aktivitas Akademik Harian</h3>
            <div class="flex items-end justify-around gap-2 h-56 pb-4 border-b border-gray-100">
                <div class="w-12 bg-emerald-600 rounded-t-sm hover:bg-emerald-700 transition-colors" style="height: 60%;"></div>
                <div class="w-12 bg-emerald-600 rounded-t-sm hover:bg-emerald-700 transition-colors" style="height: 80%;"></div>
                <div class="w-12 bg-emerald-600 rounded-t-sm hover:bg-emerald-700 transition-colors" style="height: 50%;"></div>
                <div class="w-12 bg-emerald-600 rounded-t-sm hover:bg-emerald-700 transition-colors" style="height: 90%;"></div>
                <div class="w-12 bg-emerald-600 rounded-t-sm hover:bg-emerald-700 transition-colors" style="height: 70%;"></div>
                <div class="w-12 bg-gray-300 rounded-t-sm hover:bg-gray-400 transition-colors" style="height: 30%;"></div>
            </div>
            <div class="flex justify-around text-xs font-medium text-gray-500 mt-3">
                <span>Senin</span><span>Selasa</span><span>Rabu</span><span>Kamis</span><span>Jumat</span><span>Sabtu</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-sm font-bold text-gray-800 mb-4">Menu Aksi Cepat</h3>
            <div class="space-y-3">
                <a href="#" class="flex items-center gap-3 p-3.5 rounded-lg bg-emerald-50 text-emerald-800 hover:bg-emerald-100 transition-colors group">
                    <div class="p-2 bg-white rounded-md shadow-sm group-hover:shadow transition-shadow">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    </div>
                    <span class="text-sm font-semibold">Daftarkan Santri Baru</span>
                </a>
                <a href="#" class="flex items-center gap-3 p-3.5 rounded-lg bg-emerald-50 text-emerald-800 hover:bg-emerald-100 transition-colors group">
                    <div class="p-2 bg-white rounded-md shadow-sm group-hover:shadow transition-shadow">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span class="text-sm font-semibold">Cetak Kuitansi Sya'riah</span>
                </a>
            </div>
        </div>
    </div>
</x-layouts.admin>