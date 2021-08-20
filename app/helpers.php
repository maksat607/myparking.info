<?php

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
