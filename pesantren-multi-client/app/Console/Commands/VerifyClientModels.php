<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\Client;
use App\Modules\Client\Services\AuditLogger;
use App\Policies\ClientPolicy;
use Illuminate\Console\Command;

class VerifyClientModels extends Command
{
    protected $signature = 'verify:client-models';

    protected $description = 'Verifikasi model, policy, dan audit logger Modul 5';

    public function handle(): int
    {
        $this->info('=== Models ===');
        $this->info('ClientAdminFee: App\Models\ClientAdminFee');

        $this->info('=== Policy ===');
        $policy = new ClientPolicy;
        $this->info('ClientPolicy::viewAny() siap: '.(method_exists($policy, 'viewAny') ? 'OK' : 'FAIL'));

        $this->info('=== AuditLog ===');
        $this->info('AuditLog: '.AuditLog::class);

        $client = Client::query()->first();
        if ($client === null) {
            $this->warn('Belum ada data Client.');
            return self::SUCCESS;
        }

        $this->info('=== Client sample ===');
        $this->info('Name: '.$client->name.' (id: '.$client->id.')');

        $this->info('=== Test AuditLogger ===');
        $log = app(AuditLogger::class)->record(
            modelType: 'client',
            modelId: $client->id,
            action: 'verify.test',
            before: null,
            after: ['name' => $client->name, 'account_number' => '1234567890'],
            clientId: $client->id,
        );

        $this->info('Log ID: '.$log->id);
        $sanitized = ! isset($log->changes['after']['account_number']);
        $this->info('Sanitasi field sensitif: '.($sanitized ? 'OK' : 'GAGAL'));

        return self::SUCCESS;
    }
}
