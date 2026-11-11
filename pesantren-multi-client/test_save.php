try {
    \ = \App\Models\Student::create([
        'nis' => 'TES-' . rand(100,999),
        'full_name' => 'Tes Simpan Terminal',
        'gender' => 'L',
        'status' => 'active',
        'tenant_id' => 1
    ]);
    echo '>>> BERHASIL MASUK DATABASE! ID: ' . \->id . PHP_EOL;
} catch (\Exception \) {
    echo '>>> GAGAL DATABASE: ' . \->getMessage() . PHP_EOL;
}
