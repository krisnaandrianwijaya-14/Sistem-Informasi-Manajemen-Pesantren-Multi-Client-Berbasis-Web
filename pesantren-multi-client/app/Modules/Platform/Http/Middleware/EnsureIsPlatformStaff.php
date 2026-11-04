<?php

namespace App\Modules\Platform\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureIsPlatformStaff
{
    public function handle(Request $request, Closure $next): Response
    {
        $staffRoles = ['platform_admin', 'finance_admin', 'support_specialist', 'saas_auditor'];

        // Jika user belum login ATAU role-nya tidak ada di daftar staf, tendang!
        if (!Auth::check() || !in_array(Auth::user()->role, $staffRoles)) {
            abort(403, 'Akses Ditolak. Anda bukan bagian dari staf platform SiSantri.');
        }

        return $next($request);
    }
}
