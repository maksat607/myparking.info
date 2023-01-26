<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('partners')->truncate();
        DB::table('partners')->insert([
            ['id'=>1, 'name'=> 'А-Приори (свой)', 'address'=>'А-Приори', 'partner_type_id'=> 3, 'status'=> TRUE],
            ['id'=>2, 'name'=> 'Согласие СК', 'address'=>'Москва', 'partner_type_id'=> 2, 'status'=> TRUE],
            ['id'=>3, 'name'=> 'ВСК', 'address'=>'адрес', 'partner_type_id'=> 1, 'status'=> TRUE],
            ['id'=>4, 'name'=> 'Югория Москва', 'address'=>'Москва', 'partner_type_id'=> 2, 'status'=> TRUE],
            ['id'=>5, 'name'=> 'Альфа страхование Москва', 'address'=>'Москва', 'partner_type_id'=> 2, 'status'=> TRUE],
            ['id'=>6, 'name'=> 'Юнити страхование МСК', 'address'=>'Москва', 'partner_type_id'=> 2, 'status'=> TRUE],
            ['id'=>7, 'name'=> 'ООО СК "Клувер""', 'address'=>'адрес', 'partner_type_id'=> 1, 'status'=> TRUE],
            ['id'=>8, 'name'=> 'ООО СМП-Страхование""', 'address'=>'адрес', 'partner_type_id'=> 1, 'status'=> TRUE],
            ['id'=>9, 'name'=> 'АО Страховая компания "ПАРИ""', 'address'=>'адрес', 'partner_type_id'=> 1, 'status'=> TRUE],
            ['id'=>10, 'name'=> 'КРК Страхование', 'address'=>'адрес', 'partner_type_id'=> 1, 'status'=> TRUE],
            ['id'=>11, 'name'=> 'ПАО СК ГАЙДЕ""', 'address'=>'адрес', 'partner_type_id'=> 1, 'status'=> TRUE],
            ['id'=>12, 'name'=> 'ПАО СК Росгосстрах" Москва"', 'address'=>'Москва', 'partner_type_id'=> 2, 'status'=> TRUE],
            ['id'=>13, 'name'=> 'АО СК Полис Гарант""', 'address'=>'адрес', 'partner_type_id'=> 2, 'status'=> TRUE],
            ['id'=>14, 'name'=> 'ООО РОСИНКОР Резерв', 'address'=>'адрес', 'partner_type_id'=> 1, 'status'=> TRUE],
            ['id'=>15, 'name'=> 'Ренессанс Страхование', 'address'=>'Москва', 'partner_type_id'=> 2, 'status'=> TRUE],
            ['id'=>16, 'name'=> 'ЭКИП', 'address'=>'адрес', 'partner_type_id'=> 1, 'status'=> TRUE],
            ['id'=>18, 'name'=> 'Либерти Страхование', 'address'=>'Москва', 'partner_type_id'=> 2, 'status'=> TRUE],
            ['id'=>19, 'name'=> 'Боровицкое СО', 'address'=>'адрес', 'partner_type_id'=> 1, 'status'=> TRUE],
            ['id'=>20, 'name'=> 'ООО Центральное Страховое Общество""', 'address'=>'адрес', 'partner_type_id'=> 1, 'status'=> TRUE],
            ['id'=>21, 'name'=> 'Тиньков', 'address'=>'адрес', 'partner_type_id'=> 1, 'status'=> TRUE],
            ['id'=>22, 'name'=> 'Манипуляторы', 'address'=>'адрес', 'partner_type_id'=> 1, 'status'=> TRUE],
            ['id'=>23, 'name'=> 'Автолизинг', 'address'=>'Москва', 'partner_type_id'=> 1, 'status'=> TRUE],
            ['id'=>24, 'name'=> 'Альфа страхование СПб', 'address'=>'Санкт-Петербург', 'partner_type_id'=> 2, 'status'=> TRUE],
            ['id'=>25, 'name'=> 'Европлан', 'address'=>'Москва', 'partner_type_id'=> 1, 'status'=> TRUE],
            ['id'=>26, 'name'=> 'ИАЛ Финанс', 'address'=>'Москва', 'partner_type_id'=> 1, 'status'=> TRUE],
            ['id'=>27, 'name'=> 'ИП Балашкевич Анатолий Иванович', 'address'=>'Москва', 'partner_type_id'=> 3, 'status'=> TRUE],
            ['id'=>28, 'name'=> 'Каркаде', 'address'=>'Москва', 'partner_type_id'=> 1, 'status'=> TRUE],
            ['id'=>29, 'name'=> 'Компания Автобал', 'address'=>'Москва', 'partner_type_id'=> 3, 'status'=> TRUE],
            ['id'=>30, 'name'=> 'Каршеринг', 'address'=>'Москва', 'partner_type_id'=> 3, 'status'=> TRUE],
            ['id'=>31, 'name'=> 'Контрол лизинг', 'address'=>'Москва', 'partner_type_id'=> 1, 'status'=> TRUE],
            ['id'=>32, 'name'=> 'ПАО СК Росгосстрах" СПб"', 'address'=>'Санкт-Петербург', 'partner_type_id'=> 2, 'status'=> TRUE],
            ['id'=>33, 'name'=> 'Управление корпоративным парком', 'address'=>'Москва', 'partner_type_id'=> 3, 'status'=> TRUE],
            ['id'=>34, 'name'=> 'Энитайм', 'address'=>'Москва', 'partner_type_id'=> 3, 'status'=> TRUE],
            ['id'=>35, 'name'=> 'Югория СПб', 'address'=>'Санкт-Петербург', 'partner_type_id'=> 2, 'status'=> TRUE],
            ['id'=>36, 'name'=> 'Юнити страхование СПб', 'address'=>'Санкт-Петербург', 'partner_type_id'=> 2, 'status'=> TRUE],
            ['id'=>37, 'name'=> 'Сбербанк лизинг', 'address'=>'Москва', 'partner_type_id'=> 1, 'status'=> TRUE],
            ['id'=>38, 'name'=> 'ООО Балтийский лизинг""', 'address'=>'Москва', 'partner_type_id'=> 1, 'status'=> TRUE],
            ['id'=>39, 'name'=> 'Газпромбанк Автолизинг', 'address'=>'Москва', 'partner_type_id'=> 1, 'status'=> TRUE],
            ['id'=>40, 'name'=> 'Рога', 'address'=>'Санкт-Петербург', 'partner_type_id'=> 2, 'status'=> TRUE],
            ['id'=>41, 'name'=> 'ЭНЕРГОГАРАНТ', 'address'=>'Москва', 'partner_type_id'=> 2, 'status'=> TRUE],
            ['id'=>42, 'name'=> 'КарМани МСК', 'address'=>'119019, Россия, г. Москва, ул. Воздвиженка, дом 9, строение 2, помещение 1', 'partner_type_id'=> 3, 'status'=> TRUE],
            ['id'=>43, 'name'=> 'КарМани СПб', 'address'=>'Санкт-Петербург', 'partner_type_id'=> 3, 'status'=> TRUE],
            ['id'=>44, 'name'=> 'Гудвин Экспреcc МСК', 'address'=>'Москва', 'partner_type_id'=> 3, 'status'=> TRUE],
            ['id'=>45, 'name'=> 'Гудвин Экспреcc СПб', 'address'=>'Санкт-Петербург', 'partner_type_id'=> 3, 'status'=> TRUE],


        ]);
    }
}
