<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Tenant;
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

        Tenant::create([
            'name' => 'Artur Ltda',
            'slug' => 'artur-ltda',
            'database' => 'artur_ltda',
            'total_users' => 10,
        ])->createDatabase();

        foreach (Tenant::factory(10)->create() as $tenant) {
            $tenant->createDatabase();
        }
    }
}
