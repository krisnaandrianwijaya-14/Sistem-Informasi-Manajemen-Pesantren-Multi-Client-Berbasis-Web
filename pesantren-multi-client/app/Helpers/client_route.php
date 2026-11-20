<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

if (!function_exists('client_route')) {
    function client_route(string $name, mixed $parameters = [], bool $absolute = true): string
    {
        $cleanName = str_starts_with($name, 'client.') ? substr($name, 7) : $name;

        $candidates = [
            'client.' . $cleanName,
        ];

        if (str_starts_with($cleanName, 'admin.')) {
            $candidates[] = 'client.' . substr($cleanName, 6);
        }

        $routeName = collect($candidates)->first(fn ($candidate) => Route::has($candidate))
            ?? ('client.' . $cleanName);

if (is_array($parameters) && !isset($parameters['client'])) {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();

            $defaultClient = URL::getDefaultParameters()['client']
                ?? $user?->defaultClientSlug();

            if ($defaultClient) {
                $parameters = array_merge(['client' => $defaultClient], $parameters);
            }
        }

        return route($routeName, $parameters, $absolute);
    }
}
