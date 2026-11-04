<?php
use Livewire\Component;
use App\Models\AuditLog;

new class extends Component
{
    public function render()
    {
        return view('platform::livewire.audit-logs', [
            'logs' => AuditLog::with('user')->latest()->paginate(10)
        ]);
    }
};
?>

<div class="max-w-6xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-lg font-bold text-gray-800">Audit Log Platform</h2>
    </div>
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                <th class="px-6 py-4 font-semibold">Waktu</th>
                <th class="px-6 py-4 font-semibold">User</th>
                <th class="px-6 py-4 font-semibold">Aktivitas</th>
                <th class="px-6 py-4 font-semibold">IP Address</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($logs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $log->created_at->format('d M Y, H:i') }} WIB</td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $log->user?->name ?? 'System' }}</div>
                        <div class="text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', $log->user?->role ?? '-') }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $log->description }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $log->ip_address }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-gray-400">Belum ada aktivitas tercatat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $logs->links() }}
    </div>
</div>
