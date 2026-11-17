<x-layouts.platform title="Client & Biaya Administrasi">
  @if (session('status'))
    <div class="mb-4 rounded-lg bg-green-100 px-4 py-2 text-sm text-green-800">
      {{ session('status') }}
    </div>
  @endif

  <div class="mb-4 flex items-center justify-between">
    <h1 class="text-xl font-semibold">Client & Biaya Administrasi</h1>
    <a href="{{ route('platform.clients.create') }}"
       class="inline-flex min-h-11 items-center rounded-lg bg-slate-900 px-4 text-sm font-medium text-white transition hover:bg-slate-700">
      + Daftarkan Client
    </a>
  </div>

  <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
    <table class="w-full min-w-[48rem] text-left text-sm">
      <thead class="bg-slate-100 text-xs uppercase tracking-wide text-slate-600">
        <tr>
          <th class="px-4 py-3">Client</th>
          <th class="px-4 py-3">Penanggung jawab</th>
          <th class="px-4 py-3">Fee / Santri</th>
          <th class="px-4 py-3">Status</th>
          <th class="px-4 py-3 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($clients as $client)
          @php
            $owner = $client->clientUsers->first()?->user;
            $bank = $client->bankAccounts->first();
            $fee = $client->adminFees->first();
          @endphp
          <tr class="border-t border-slate-200">
            <td class="px-4 py-3 font-medium">
              {{ $client->name }}
              <span class="ms-1 font-mono text-xs text-slate-500">{{ $client->slug }}</span>
            </td>
            <td class="px-4 py-3">
              {{ $owner?->name ?? '—' }}
              @if ($bank) · {{ $bank->bank_code }} ••{{ $bank->account_last4 }} @endif
            </td>
            <td class="px-4 py-3 font-semibold">
              Rp {{ number_format((int) ($fee?->fee_per_student ?? 0), 0, ',', '.') }}
            </td>
            <td class="px-4 py-3">
              <span @class([
                'inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium',
                'bg-green-100 text-green-800' => $client->status === 'active',
                'bg-red-100 text-red-800' => $client->status === 'suspended',
                'bg-slate-100 text-slate-700' => ! in_array($client->status, ['active', 'suspended'], true),
              ])>
                {{ strtoupper($client->status) }}
              </span>
            </td>
            <td class="px-4 py-3 text-right">
              <div class="flex items-center justify-end gap-3">
                <a href="{{ route('platform.clients.edit', $client) }}"
                   class="text-sm font-medium underline">Kelola</a>

                <form method="POST"
                      action="{{ route('platform.clients.status', $client) }}"
                      class="inline">
                  @csrf
                  <input type="hidden" name="status"
                         value="{{ $client->status === 'active' ? 'suspended' : 'active' }}">
                  <button type="submit"
                          class="text-sm font-medium hover:underline
                                 {{ $client->status === 'active' ? 'text-red-600' : 'text-green-600' }}">
                    {{ $client->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="px-4 py-10 text-center">
            <p class="text-sm font-medium text-slate-800">Belum ada client</p>
            <p class="text-sm text-slate-500">Daftarkan client pertama dengan tombol di atas.</p>
          </td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</x-layouts.platform>
