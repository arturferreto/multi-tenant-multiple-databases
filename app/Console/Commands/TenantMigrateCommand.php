<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class TenantMigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:migrate {tenant?*}
            {--fresh : Drops all tables and re-executes}
            {--seed : Indicates if the seed task should be re-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the database migrations for a specific or all tenants';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->confirmToProceed();

        $tenants = $this->argument('tenant')
            ? Tenant::query()->whereIn('id', $this->argument('tenant'))->get()
            : Tenant::all();

        if ($tenants->isEmpty()) {
            $this->info('No tenants found.');

            return;
        }

        $tenants->each(fn ($tenant) => $this->migrateTenant($tenant));
    }

    /**
     * Run the migration for the specific tenant.
     */
    private function migrateTenant($tenant): void
    {
        $this->info("Migrating Tenant #{$tenant->id}: {$tenant->name} ({$tenant->database})");

        $tenant->configure()->use();

        $command = $this->option('fresh') ? 'migrate:fresh' : 'migrate';

        $options = [
            '--database' => 'tenant',
            '--path' => 'database/migrations',
            '--force' => true,
        ];

        if ($this->option('seed')) {
            $options['--seed'] = true;
        }

        $this->call($command, $options);
    }

    /**
     * Confirm running in production environment.
     */
    protected function confirmToProceed(): void
    {
        if (app()->environment('production') && (! $this->confirm('Do you really wish to run this command in production?'))) {
            $this->info('Command aborted!');

            exit;
        }
    }
}
