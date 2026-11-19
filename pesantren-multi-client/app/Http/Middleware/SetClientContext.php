<?php

namespace App\Http\Middleware;

use App\Models\Client;
use App\Support\ClientContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

final class SetClientContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $client = $request->route('client');

        Log::info('SetClientContext: masuk', ['client' => is_object($client) ? get_class($client) : $client]);

        if (! $client instanceof Client) {
            $slug = is_object($client) && method_exists($client, 'getRouteKey')
                ? $client->getRouteKey()
                : (string) $client;
            $client = Client::query()->where('slug', $slug)->first();
            Log::info('SetClientContext: fallback', ['slug' => $slug, 'found' => $client !== null]);
        }

        // JANGAN abort — hanya set context kalau ada
        if ($client instanceof Client) {
            $context = app(ClientContext::class);
            $context->set($client);

            try {
                return $next($request);
            } finally {
                $context->clear();
            }
        }

        Log::warning('SetClientContext: client tidak ketemu, lanjut tanpa context');
        return $next($request);
    }
}