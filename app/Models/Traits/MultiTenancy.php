<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\DB;

trait MultiTenancy
{
    /**
     * Configure the tenant connection.
     *
     * @var string
     */
    public function configure(): self
    {
        $this->createDatabase();

        config()->set([
            'database.connections.tenant.database' => $this->database,
            'cache.prefix' => $this->database,
        ]);

        DB::purge('tenant');

        app('cache')->forgetDriver(config('cache.default'));

        return $this;
    }

    /**
     * Connect to the tenant database.
     */
    public function use(): self
    {
        app()->forgetInstance('tenant');

        app()->instance('tenant', $this);

        return $this;
    }

    /**
     * Check if the tenant database exists.
     */
    public function checkDatabase(): bool
    {
        return (bool) DB::select("SELECT 1 FROM pg_database WHERE datname = '$this->database'");
    }

    /**
     * Create the tenant database.
     */
    public function createDatabase(): bool
    {
        if ($this->checkDatabase()) {
            return false;
        }

        DB::statement("CREATE DATABASE $this->database");

        return true;
    }
}
