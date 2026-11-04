<?php

namespace App\Modules\Platform\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Client;
use App\Modules\Platform\Support\ClientContext;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SetClientContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('client');

        if ($slug) {
            $client = Client::where('slug', $slug)->where('status', 'active')->firstOrFail();

            // Ini kode yang tadi merah di VS Code Anda.
            // Di Laravel, Auth::check() dan Auth::user() adalah fungsi yang 100% valid.
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if (!$user || !$user->hasAccessToClient($client->id)) {
                abort(403, 'Akses ditolak ke pesantren ini.');
            }

            app(ClientContext::class)->set($client);
        }

        return $next($request);
    }
}
