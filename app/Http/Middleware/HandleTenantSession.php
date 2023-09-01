<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleTenantSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has('tenant_id')) {
            $request->session()->put('tenant_id', app('tenant')->id);

            return $next($request);
        }

        if ($request->session()->get('tenant_id') !== app('tenant')->id) {
            abort(401);
        }

        return $next($request);
    }
}
