<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Modules\Student\Services\StudentQrService;
use Illuminate\Console\Command;

class SeedStudentQr extends Command
{
    protected $signature = 'students:seed-qr {--force : Terbitkan ulang semua QR}';

    protected $description = 'Terbitkan QR static untuk santri yang belum punya hash';

    public function handle(StudentQrService $service): int
    {
        $count = 0;
        $fail = 0;

        // Ambil yang belum ada hash, atau semua kalau --force
        $query = Student::query();
        if (! $this->option('force')) {
            $query->whereNull('qr_token_hash');
        }

        $query->chunkById(100, function ($students) use ($service, &$count, &$fail): void {
            foreach ($students as $student) {
                try {
                    $service->issue($student, force: true);
                    $count++;
                } catch (\Throwable $e) {
                    $this->error("Gagal {$student->name}: {$e->getMessage()}");
                    $fail++;
                }
            }
        });

        $this->info("{$count} QR santri diterbitkan.");
        if ($fail > 0) {
            $this->warn("{$fail} gagal.");
        }

        return self::SUCCESS;
    }
}
