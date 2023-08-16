<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LandlordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('DROP EXTENSION IF EXISTS unaccent');
        DB::statement('CREATE EXTENSION unaccent');

        \App\Models\Tenant::create([
            'name' => 'Artur Ltda',
            'slug' => 'artur-ltda',
            'database' => 'arturltda',
            'total_users' => 10,
        ]);

        \App\Models\Tenant::factory(10)->create();
    }
}
