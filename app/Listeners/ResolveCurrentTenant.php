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
        $tenant = $event->user->setting->fav_tenant_id !== null
            ? $event->user->setting->fav_tenant_id
            : ($event->user->tenants->count() === 1 ? $event->user->tenants->first()->id : null);

        $event->user->updateCurrentTenant($tenant);
    }
}
