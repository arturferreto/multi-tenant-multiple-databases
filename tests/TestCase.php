<?php

namespace Tests;

use App\Models\Company;
use App\Models\Tenant;
use App\Models\User;
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
            'name' => 'test',
            'slug' => 'test',
            'database' => 'database/test.sqlite',
            'total_users' => 100,
        ]);

        config(['database.connections.tenant.driver' => 'sqlite']);

        $this->artisan("tenant:migrate $tenant->id --fresh");

        $tenant->configure()->use();
    }

    public function createUser(): User
    {
        $user = User::factory()->create([
            'current_tenant_id' => app('tenant')->id,
        ]);

        $user->tenants()->attach(app('tenant'));

        $user->setting()->create([
            'fav_tenant_id' => app('tenant')->id,
        ]);

        return $user;
    }
}
