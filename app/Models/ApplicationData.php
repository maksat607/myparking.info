<?php


namespace App\Models;


class ApplicationData
{
    public $id = null;
    public $car_title = null;
    public $vin = null;
    public $license_plate = null;
    public $sts = null;
    public $pts = null;
    public $pts_type = null;
    public $sts_provided = false;
    public $pts_provided = false;
    public $car_key_quantity = null;
    public $year = null;
    public $milage = null;
    public $owner_number = null;
    public $color = null;
    public $on_sale = false;
    public $price = null;
    public $exterior_damage = null;
    public $interior_damage = null;
    public $condition_electric = null;
    public $condition_engine = null;
    public $condition_gear = null;
    public $condition_transmission = null;
    public $services = null;
    public $car_additional = null;
    public $car_type_id = null;
    public $car_mark_id = null;
    public $car_model_id = null;
    public $car_generation_id = null;
    public $car_series_id = null;
    public $car_modification_id = null;
    public $car_engine_id = null;
    public $car_transmission_id = null;
    public $car_gear_id = null;
    public $internal_id = null;
    public $external_id = null;
    public $arriving_method = '0';
    public $tow_truck_payment_id = null;
    public $parking_id = null;
    public $partner_id = null;
    public $presentation_contract_id = null;
    public $courier_fullname = null;
    public $courier_phone = null;
    public $courier_type_id = null;
    public $client_id = null;
    public $responsible_user_id = null;
    public $parking_place_number = null;
    public $parking_car_sticker = null;
    public $parking_cost = null;
    public $parked_days = null;
    public $status_id = null;
    public $created_user_id = null;
    public $arriving_interval = null;
    public $arriving_at = null;
    public $arrived_at = null;
    public $issued_at = null;
    public $user_id = null;
    public $created_at = null;
    public $updated_at = null;

    public function getParamsToArray() {
        return get_object_vars($this);
    }
}
