<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('car_marks')->truncate();
        DB::table('car_models')->truncate();
        DB::table('car_generations')->truncate();
        DB::table('car_series')->truncate();
        DB::table('car_modifications')->truncate();
        DB::table('car_characteristics')->truncate();
        DB::table('car_characteristic_values')->truncate();

        $path = __DIR__ . '/sql/car_marks.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Car Marks seeded!');

        $path = __DIR__ . '/sql/car_models.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Car Models seeded!');

        $path = __DIR__ . '/sql/car_generations.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Car Generations seeded!');

        $path = __DIR__ . '/sql/car_series.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Car Series seeded!');

        $path = __DIR__ . '/sql/car_characteristics.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Car Characteristics seeded!');

        $path = __DIR__ . '/sql/car_modifications.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Car Modifications seeded!');

//        $path = __DIR__ . '/sql/car_characteristic_values.sql';
//        DB::unprepared(file_get_contents($path));
//        $this->command->info('Car Characteristic Values seeded!');
    }
}
