<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class HandleTenantConfiguration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->current_tenant_id === null || $request->user()->currentTenant->deleted_at !== null) {
            return redirect()->route('choose-tenant.show');
        }

        $request->user()->currentTenant->configure()->use();

        return $next($request);
    }
}
