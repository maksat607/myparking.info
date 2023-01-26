<?php
namespace App\Helpers;
class HideUser
{


    public function getFoo(){

    }
    public static function bar(){
        return app(HideUser::class)->getFoo();
    }
    public static function setfoo($foo){
        $parsing = app(ParseString::class);
        $parsing->foo = $foo;
        return $parsing;
    }
}
