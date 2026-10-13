<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\ClientAdminFee;
use App\Models\ClientBankAccount;
use App\Models\ClientUser;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class ClientManagementTest extends TestCase
{
    use RefreshDatabase;

    private function platformAdmin(): User
    {
        return User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'platform@test.local',
            'password' => Hash::make('password'),
            'role' => 'platform_admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }

    private function makeClient(array $attrs = []): Client
{
    return Client::create(array_merge([
        'name' => 'Pesantren Test '.uniqid(),
        'slug' => 'test-'.uniqid(),
        'status' => 'active',
    ], $attrs));
}

    public function test_platform_admin_can_create_client_with_fee_and_bank(): void
    {
        $this->actingAs($this->platformAdmin());

        $this->post(route('platform.clients.store'), [
            'name' => 'Pesantren Al-Fattah',
            'slug' => 'al-fattah',
            'fee_per_student' => 7000,
            'pic_name' => 'Ust. Ahmad',
            'pic_email' => 'ahmad@alfattah.test',
            'bank_code' => 'BCA',
            'account_holder' => 'Ahmad',
            'account_number' => '1234567890',
        ])->assertRedirect();

        $client = Client::where('slug', 'al-fattah')->firstOrFail();

        // Fee aktif dibuat
        $this->assertSame(
            7000,
            $client->adminFees()->withoutGlobalScope('client')->whereNull('valid_to')->first()->fee_per_student
        );

        // Owner ditetapkan
        $owner = ClientUser::where('client_id', $client->id)->where('role', 'owner')->first();
        $this->assertNotNull($owner);
        $this->assertTrue($owner->is_active);

        // Bank account dengan last4
        $bank = ClientBankAccount::where('client_id', $client->id)->firstOrFail();
        $this->assertSame('7890', $bank->account_last4);
        $this->assertSame('unverified', $bank->status);
    }

    public function test_only_one_active_fee_per_client_enforced_by_db_guard(): void
{
    $client = $this->makeClient();

    ClientAdminFee::query()->forceCreate([
        'client_id' => $client->id,
        'fee_per_student' => 5000,
        'valid_from' => now()->subMonth(),
        'valid_to' => null,
    ]);

    $this->expectException(QueryException::class);

    ClientAdminFee::query()->forceCreate([
        'client_id' => $client->id,
        'fee_per_student' => 8000,
        'valid_from' => now(),
        'valid_to' => null,
    ]);
}

public function test_schedule_new_fee_closes_previous_version(): void
{
    $this->actingAs($this->platformAdmin());
    $client = $this->makeClient();

    ClientAdminFee::query()->forceCreate([
        'client_id' => $client->id,
        'fee_per_student' => 5000,
        'valid_from' => now()->subMonth(),
        'valid_to' => null,
    ]);

    $effectiveAt = now()->addDay()->startOfSecond();

    $this->post(route('platform.clients.fee.store', $client), [
        'fee_per_student' => 8000,
        'effective_at' => $effectiveAt->format('Y-m-d\TH:i'),
    ])->assertRedirect();

    $fees = ClientAdminFee::withoutGlobalScope('client')
        ->where('client_id', $client->id)
        ->orderBy('valid_from')
        ->get();

    $this->assertCount(2, $fees);
    $this->assertNotNull($fees[0]->valid_to, 'versi lama harus ditutup');
    $this->assertNull($fees[1]->valid_to, 'versi baru harus aktif');
}

public function test_bank_account_stored_encrypted_with_last4(): void
{
    $client = $this->makeClient();

    $account = ClientBankAccount::query()->forceCreate([
        'client_id' => $client->id,
        'bank_code' => 'BCA',
        'account_holder' => 'Budi',
        'account_last4' => '7890',
        'account_number_cipher' => '1234567890',
        'status' => 'unverified',
        'is_primary' => false,
    ]);

    $raw = DB::table('client_bank_accounts')->where('id', $account->id)->first();
    $this->assertStringNotContainsString('1234567890', (string) $raw->account_number_cipher);

    $this->assertSame('1234567890', $account->fresh()->account_number_cipher);
}

    public function test_cannot_remove_last_owner(): void
    {
        $client = Client::factory()->create();
        $owner = User::factory()->create();

        ClientUser::create([
            'client_id' => $client->id,
            'user_id' => $owner->id,
            'role' => 'owner',
            'is_active' => true,
        ]);

        $this->expectException(\DomainException::class);

        app(\App\Modules\Client\Services\AssignClientRole::class)
            ->remove($client, $owner, 'owner');
    }

    public function test_non_platform_admin_is_forbidden(): void
    {
        $outsider = User::factory()->create([
            'role' => 'guru',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($outsider)
            ->get(route('platform.clients.index'))
            ->assertForbidden();
    }
}
