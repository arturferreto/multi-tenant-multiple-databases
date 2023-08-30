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
    public function show(): Response|RedirectResponse
    {
        $user = auth()->user();

        if ($user->tenants()->withTrashed()->count() === 1) {
            $user->updateCurrentTenant($user->tenants()->first()->id);

            $user->setting->update(['fav_tenant_id' => $user->tenants()->first()->id]);

            return redirect()->intended(RouteServiceProvider::HOME);
        }

        return Inertia::render('Auth/ChooseTenant', [
            'tenants' => auth()->user()->tenants()->withTrashed()->get(),
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
