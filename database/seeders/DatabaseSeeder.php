<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call(PartnerTypeSeeder::class);
        $this->call(PartnerSeeder::class);
        $this->call(StatusSeeder::class);
        $this->call(CarTypeSeeder::class);
//        $this->call(CarTablesSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(SuperAdminSeeder::class);
    }
}
