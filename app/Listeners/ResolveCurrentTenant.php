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
        $event->user->setting->fav_tenant_id !== null
            ? $event->user->update(['current_tenant_id' => $event->user->setting->fav_tenant_id])
            : $event->user->update(['current_tenant_id' => null]);
    }
}