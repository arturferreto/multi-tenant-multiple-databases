<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ResolveCurrentTenant
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $user = $event->user;
        $setting = $user->setting;
        $tenants = $user->tenants;

        $tenant = $setting->fav_tenant_id !== null
            ? $setting->fav_tenant_id
            : ($tenants->count() === 1 ? $tenants->first()->id : null);

        $user->update(['current_tenant_id' => $tenant]);
        $user->save();
    }
}
