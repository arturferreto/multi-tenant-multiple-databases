<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LandlordMigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'landlord:migrate
                {--fresh : Drops all tables and re-executes}
                {--seed : Indicates if the seed task should be re-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the database migrations for the landlord connection';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->confirmRunningInProduction();

        $this->info('Migrating Landlord Connection');

        $command = $this->option('fresh') ? 'migrate:fresh' : 'migrate';

        $options = [
            '--database' => 'landlord',
            '--path' => 'database/migrations/landlord',
            '--force' => true,
        ];

        if ($this->option('seed')) {
            $options['--seeder'] = 'LandlordSeeder';
        }

        $this->call($command, $options);
    }

    /**
     * Confirm running in production environment.
     */
    protected function confirmRunningInProduction(): void
    {
        if (app()->environment('production') && (! $this->confirm('Do you really wish to run this command in production?'))) {
            $this->info('Command aborted!');

            exit;
        }
    }
}
