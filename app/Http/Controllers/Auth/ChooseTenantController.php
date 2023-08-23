<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChooseTenantRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ChooseTenantController extends Controller
{
    /**
     * Show the change tenant view.
     */
    public function show(): Response
    {
        return Inertia::render('Auth/ChooseTenant', [
            'tenants' => auth()->user()->tenants()->withTrashed()->get(),
            'current_tenant_id' => auth()->user()->current_tenant_id,
            'fav_tenant_id' => auth()->user()->setting->fav_tenant_id,
        ]);
    }

    /**
     * Change the user's current tenant.
     */
    public function store(ChooseTenantRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->updateCurrentTenant($request->tenant_id);

        if ($request->fav_tenant_id === true) {
            $user->setting->fav_tenant_id = $request->tenant_id;
            $user->setting->save();
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
