<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('car_types')->truncate();

        DB::table('car_types')->insert([
            ['name' => "Легковой автомобиль"],
            ['name' => "Грузовик"],
            ['name' => "Мотоцикл"],
            ['name' => "Фургон"],
            ['name' => "Прочее"],
            ['name' => "Автобус"],
            ['name' => "Прицеп"],
            ['name' => "Автопоезд/ТС на сцепке"],
            ['name' => "Бульдозеры"],
            ['name' => "Автопогрузчики"],
            ['name' => "Экскаваторы"],
            ['name' => "Сельхоз-техника"],
            ['name' => "Коммунальная"],
            ['name' => "Самопогрузчики"],
            ['name' => "Строительная"],
            ['name' => "Автокраны"],
        ]);
    }
}
