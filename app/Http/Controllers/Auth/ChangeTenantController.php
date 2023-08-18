<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangeTenantRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ChangeTenantController extends Controller
{
    /**
     * Show the change tenant view.
     */
    public function show(): Response
    {
        return Inertia::render('Auth/ChangeTenant', [
            'tenants' => auth()->user()->tenants,
        ]);
    }

    /**
     * Change the user's current tenant.
     */
    public function store(ChangeTenantRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->current_tenant_id = $request->tenant_id;
        $user->save();

        if ($request->fav_tenant_id === true) {
            $user->setting->fav_tenant_id = $request->tenant_id;
            $user->setting->save();
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
