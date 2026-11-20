<?php

namespace App\Modules\Platform\Http\Middleware;

use App\Models\Client;
use App\Models\User;
use App\Modules\Platform\Support\ClientContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetClientContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $routeClient = $request->route('client');
        $slug = $routeClient instanceof Client ? $routeClient->slug : $routeClient;

        if ($slug) {
            $client = Client::where('slug', $slug)->where('status', 'active')->firstOrFail();

            /** @var \App\Models\User|null $user */
            $user = Auth::user();

            if (!$user || !$user->hasAccessToClient($client->id)) {
                abort(403, 'Akses ditolak ke pesantren ini.');
            }

            app(ClientContext::class)->set($client);

            // Isi otomatis parameter {client} untuk helper route() dan hapus dari parameter controller
            URL::defaults(['client' => $client->slug]);
            $request->route()->forgetParameter('client');
        }

        return $next($request);
    }
}
