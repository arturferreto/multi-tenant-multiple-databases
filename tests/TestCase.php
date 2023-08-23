<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

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

            'database.connections.tenant' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
            ]
        ]);

        $this->artisan('landlord:migrate --fresh');
        $this->artisan('tenant:migrate --fresh');

        $this->app[Kernel::class]->setArtisan(null);
    }
}
