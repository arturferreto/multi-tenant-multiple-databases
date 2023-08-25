<?php

namespace Tests;

use App\Models\Company;
use App\Models\Tenant;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.connections.landlord' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
            ],
        ]);

        $this->artisan('landlord:migrate --fresh');

        $this->app[Kernel::class]->setArtisan(null);

        $this->setAndConfigureTenant();
    }

    private function setAndConfigureTenant(): void
    {
        $tenant = Tenant::firstOrCreate([
            'name' => 'Tenant',
            'slug' => 'tenant',
            'database' => 'database/tenant.sqlite',
            'total_users' => 100,
        ]);

        config([
            'database.connections.tenant' => [
                'driver' => 'sqlite',
                'database' => $tenant->database,
            ],
            'cache.prefix' => $tenant->database,
        ]);

        DB::purge('tenant');

        app('cache')->forgetDriver(config('cache.default'));

        app()->forgetInstance('tenant');

        app()->instance('tenant', $tenant);

        $this->artisan("tenant:migrate $tenant->id --fresh");
    }
}
