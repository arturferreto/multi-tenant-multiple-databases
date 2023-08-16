<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('DROP EXTENSION IF EXISTS unaccent');
        DB::statement('CREATE EXTENSION unaccent');

//        $this->call([
//            //
//        ]);
//
//        \App\Models\Company::create([
//            'cnpj' => '00000000000000',
//            'legal_name' => app('tenant')->name,
//            'profile_name' => app('tenant')->name,
//        ]);
    }
}
