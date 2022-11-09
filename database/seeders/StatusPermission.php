<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StatusPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->insert([
            'name' =>'Модерация',
            'code' => 'moderation',
        ]);

        DB::table('permissions')->insert([
            'name' =>'notify_app_moderation',
            'guard_name' => 'web',
        ]);


    }
}
