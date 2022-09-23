<?php
function makeClickableApplicationNotification($str,$app_id,$user_id){
    $part1 = explode('авто',$str);
    $part2 = explode(')',$part1[1]);
    $str = str_replace($part2[0],' <a href="#" class = "app-notification theme-blue" data-app-id='.$app_id.'>'.$part2[0].'</a>',$str );
    $pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i';
    if(preg_match_all($pattern, $str, $matches)){
        $str = str_replace(collect($matches[0])->first(),' <a href="#" class = "theme-blue message-user-show-modal" data-user-id='.$user_id.'>'.collect($matches[0])->first().'</a>',$str );
    }
    return $str;
}
function makeClickableUserNotification($str,$user_id){
    $pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i';
    if(preg_match_all($pattern, $str, $matches)){
        $str = str_replace(collect($matches[0])->first(),' <a href="#" class = "theme-blue message-user-show-modal" data-user-id='.$user_id.'>'.collect($matches[0])->first().'</a>',$str );
    }
    return $str;
}

function isNotAdminRole($role){
    if('SuperAdmin' === $role) return false;
    if('Admin' === $role) return false;
    return true;
}

function createPriceList($car_types, $prices = null){
    return $car_types->map(function($car) use ($prices) {
        $price = null;
        if(isset($prices)) {
            $price = $prices->first(function($price) use ($car){
                return $price->car_type_id == $car->id;
            });
        }
        $part2=[];

        //$f1 = str_replace($part2[0],'<span class = "app-notification" data-app-id='.'23'.'>'.$part2[0].'</span>',$str )

        if($price) {
            return (object) [
                'id' => $price->id,
                'car_type_id' => $car->id,
                'car_type_name' => $car->name,
                'discount_price' => $price->discount_price,
                'regular_price' => $price->regular_price,
                'free_days' => $price->free_days,
            ];
        } else {
            return (object) [
                'id' => null,
                'car_type_id' => $car->id,
                'car_type_name' => $car->name,
                'discount_price' => 0,
                'regular_price' => 0,
                'free_days' => 0,
            ];
        }
    });

}
