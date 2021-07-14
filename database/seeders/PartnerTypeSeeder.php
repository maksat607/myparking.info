<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartnerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('partner_types')->truncate();
        DB::table('partner_types')->insert([
            ['name' =>'Лизинговая компания'],
            ['name' =>'Страховая компания'],
            ['name' =>'Другое']
        ]);
    }
}
