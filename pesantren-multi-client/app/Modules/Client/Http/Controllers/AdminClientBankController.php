<?php

namespace App\Modules\Client\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientBankAccount;
use App\Modules\Client\Services\ManageBankAccount;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class AdminClientBankController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private ManageBankAccount $service) {}

    public function store(Request $request, Client $client): RedirectResponse
    {
        $this->authorize('manageBank', $client);

        $data = $request->validate([
            'bank_code' => ['required', 'string', 'max:20'],
            'account_holder' => ['required', 'string', 'max:120'],
            'account_number' => ['required', 'digits_between:6,20'],
        ]);

        $this->service->store($client, $data);

        return back()->with('status', 'Rekening ditambahkan (menunggu verifikasi).');
    }

    public function verify(Request $request, Client $client, ClientBankAccount $account): RedirectResponse
    {
        $this->authorize('manageBank', $client);
        abort_unless($account->client_id === $client->id, 404);

        $this->service->verify($account, $request->boolean('approve'));

        return back()->with('status', 'Status rekening diperbarui.');
    }

    public function makePrimary(Client $client, ClientBankAccount $account): RedirectResponse
    {
        $this->authorize('manageBank', $client);
        abort_unless($account->client_id === $client->id, 404);

        $this->service->makePrimary($account);

        return back()->with('status', 'Rekening utama diperbarui.');
    }
}
