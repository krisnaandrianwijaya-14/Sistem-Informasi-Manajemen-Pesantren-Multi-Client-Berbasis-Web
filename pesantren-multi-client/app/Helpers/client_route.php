<?php

if (! function_exists('client_route')) {
    /**
     * Generate route URL dengan parameter client otomatis.
     *
     * @param string $name
     * @param array $parameters
     * @return string
     */
    function client_route(string $name, array $parameters = []): string
    {
        // Ambil client dari route saat ini
        $client = request()->route('client');

        if ($client) {
            $parameters['client'] = $client instanceof \App\Models\Client
                ? $client
                : $client;
        }

        // Fallback ke defaultClientSlug user
        if (! isset($parameters['client']) || ! $parameters['client']) {
            $user = auth()->user();
            $parameters['client'] = $user?->defaultClientSlug() ?? 'demo';
        }

        return route($name, $parameters);
    }
}