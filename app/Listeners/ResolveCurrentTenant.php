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
            : (
                $event->user->tenants->count() === 1
                    ? $this->resolveTenant($event->user)
                    : null
            );

        $event->user->updateCurrentTenant($tenant);
    }

    /**
     * Resolve the tenant to be used.
     */
    public function resolveTenant(object $user): int
    {
        $tenant = $user->tenants->first();

        $user->setting->update(['fav_tenant_id' => $tenant->id]);

        return $tenant->id;
    }
}
