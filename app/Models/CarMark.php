<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarMark extends Model
{
    use HasFactory;

    public static function setLogo($marks) {
        foreach ($marks as $key => $mark) {
            $name = strtolower( $mark->name );
            if (preg_match('/[^A-Za-z0-9_\-]/', $name)) {
                $name = preg_replace('/[^A-Za-z0-9_\-]/', '', $name);
            }
            $name = preg_replace('|-+|', '-', $name);
            //echo $name . '.png<br/>';
            if (file_exists( public_path() . '/images/mark-logo/' . $name . '.png')){
                $marks[$key]->logo_url = asset('images/mark-logo/' . $name . '.png');
            }
        }
        return $marks;
    }
}
