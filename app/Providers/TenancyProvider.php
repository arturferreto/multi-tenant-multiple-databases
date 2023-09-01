<?php

namespace App\Providers;

use App\Models\Tenant;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\ServiceProvider;

class TenancyProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->configureRequest();
        $this->configureQueue();
    }

    /**
     * Configure the request to use the current tenant.
     */
    protected function configureRequest(): void
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        $slug = explode('.', $this->app['request']->getHost())[0];

        $tenant = Tenant::allCached()->where('slug', $slug)->first();

        if (! $tenant) {
            abort(404);
        }

        $tenant->configure()->use();
    }

    /**
     * Configure the queue to use the current tenant.
     */
    protected function configureQueue(): void
    {
        $this->app['queue']->createPayloadUsing(function () {
            return $this->app['tenant'] ? ['tenant_id' => $this->app['tenant']->id] : [];
        });

        $this->app['events']->listen(JobProcessing::class, function (JobProcessing $event) {
            if (isset($event->job->payload()['tenant_id'])) {
                Tenant::find($event->job->payload()['tenant_id'])->configure()->use();
            }
        });
    }
}
