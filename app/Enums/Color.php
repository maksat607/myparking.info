<?php


namespace App\Enums;


use stdClass;

class Color
{
    const COLORS = [
        ['value' => '#FAFBFB', 'label' => 'Белый'],
        ['value' => '#C1C1C1', 'label' => 'Серебристый'],
        ['value'=> '#9C9999', 'label'=> 'Серый'],
        ['value'=> '#000000', 'label'=> 'Черный'],
        ['value'=> '#FFEFD5', 'label'=> 'Бежевый'],
        ['value'=> '#FDE910', 'label'=> 'Желтый'],
        ['value'=> '#FABE00', 'label'=> 'Золотистый'],
        ['value'=> '#FF9966', 'label'=> 'Оранжевый'],
        ['value'=> '#FFC0CB', 'label'=> 'Розовый'],
        ['value'=> '#FF2600', 'label'=> 'Красный'],
        ['value'=> '#CC1D33', 'label'=> 'Пурпурный'],
        ['value'=> '#926547', 'label'=> 'Коричневый'],
        ['value'=> '#0088ff', 'label'=> 'Голубой'],
        ['value'=> '#0433FF', 'label'=> 'Синий'],
        ['value'=> '#9966CC', 'label'=> 'Фиолетовый'],
        ['value'=> '#35BA2B', 'label'=> 'Зеленый']
    ];

    public static function getColors()
    {
        return collect(self::COLORS);
    }
}


