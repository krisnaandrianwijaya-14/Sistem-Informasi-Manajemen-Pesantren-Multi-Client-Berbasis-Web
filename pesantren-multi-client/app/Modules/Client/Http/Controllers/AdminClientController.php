<?php

namespace App\Modules\Client\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientAdminFee;
use App\Modules\Client\Http\Requests\ScheduleAdminFeeRequest;
use App\Modules\Client\Http\Requests\StoreClientRequest;
use App\Modules\Client\Services\ChangeAdminFeeSchedule;
use App\Modules\Client\Services\CreateClient;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;

final class AdminClientController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private CreateClient $createClient) {}

    public function index(): View
    {
        $this->authorize('viewAny', Client::class);

        $clients = Client::query()
            ->with([
                'adminFees' => fn ($q) => $q->withoutGlobalScope('client')->effectiveAt(now()),
                'bankAccounts' => fn ($q) => $q->where('is_primary', true),
                'clientUsers' => fn ($q) => $q->where('role', 'owner')->with('user'),
            ])
            ->orderBy('name')
            ->get();

        return view('client::admin.clients.index', compact('clients'));
    }

    public function create(): View
    {
        $this->authorize('create', Client::class);

        return view('client::admin.clients.create');
    }

    public function store(StoreClientRequest $request): RedirectResponse
    {
        $client = $this->createClient->handle($request->validated());

        return redirect()
            ->route('platform.clients.edit', $client)
            ->with('status', "Client \"{$client->name}\" dibuat dengan fee administrasi awal.");
    }

    public function edit(Client $client): View
    {
        $this->authorize('update', $client);

        $client->load(['bankAccounts']);

        $fees = $client->adminFees()
            ->withoutGlobalScope('client')
            ->orderByDesc('valid_from')
            ->get();

        $activeFee = ClientAdminFee::query()
            ->withoutGlobalScope('client')
            ->where('client_id', $client->id)
            ->effectiveAt(now())
            ->first();

        $members = $client->clientUsers()->with('user')->get();

        return view('client::admin.clients.edit',
            compact('client', 'fees', 'activeFee', 'members'));
    }

    public function scheduleFee(ScheduleAdminFeeRequest $request, Client $client): RedirectResponse
    {
        app(ChangeAdminFeeSchedule::class)->handle(
            $client,
            (int) $request->validated('fee_per_student'),
            \Carbon\Carbon::parse($request->validated('effective_at')),
        );

        return back()->with('status', 'Skema biaya administrasi dijadwalkan.');
    }
}
