<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarSeries extends Model
{
    use HasFactory;

    //
    const CAR_BODY_NAMES = [
        'Седан' => 'sedan',
        'Купе' => 'coupe',
        'Хардтоп' => 'coupe',
        'Тарга' => 'coupe',
        'Кабриолет' => 'cabrio',
        'Родстер' => 'cabrio',
        'Хетчбэк' => 'hatchback',
        'Хетчбэк 3 дв' => 'hatchback-3-doors',
        'Хетчбэк 5 дв' => 'hatchback-5-doors',
        'Кроссовер' => 'jeep',
        'Кроссовер 3 дв' => 'jeep-3-doors',
        'Кроссовер 5 дв' => 'jeep-5-doors',
        'Внедорожник' => 'jeep',
        'Внедорожник 3 дв' => 'jeep-3-doors',
        'Внедорожник 5 дв' => 'jeep-5-doors',
        'Лифтбэк' => 'liftback',
        'Универсал' => 'wagon',
        'Минивэн' => 'van',
        'Пикап' => 'pickup',
        'Лимузин' => 'limo',
    ];
    public function getBodyNameAttribute()
    {
        $result = "sedan";
        foreach (self::CAR_BODY_NAMES as $key => $keyRes) {
            $keyU = mb_strtoupper(str_replace(array(' ', '-'), "", $key), 'utf-8');
            $string = mb_strtoupper(str_replace(array(' ', '-'), "", $this->attributes['name']), 'utf-8');

            if (stripos($string, $keyU) !== false) {
                $result = $keyRes;
                break;
            }
        }
        return $result;
    }
}
