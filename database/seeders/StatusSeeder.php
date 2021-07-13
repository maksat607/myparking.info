<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->truncate();
        DB::table('statuses')->insert([
            ['name' =>'Черновик',                       'code' => 'draft', 'color'=> null, 'rank'=>0],
            ['name' =>'Хранение',                       'code' => 'storage', 'color'=> null, 'rank'=>0],
            ['name' =>'Выдано',                         'code' => 'issued', 'color'=> null, 'rank'=>0],
            ['name' =>'Отклонена в хранении',           'code' => 'denied-for-storage', 'color'=> null, 'rank'=>2],
            ['name' =>'Отклонена партнером',            'code' => 'cancelled-by-partner', 'color'=> null, 'rank'=>0],
            ['name' =>'Отклонена нами',                 'code' => 'cancelled-by-us', 'color'=> null, 'rank'=>0],
        ]);
    }
}
