<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id ,
            "car_title" => $this->car_title,
            "vin" => $this->vin,
            "license_plate" => $this->license_plate,
            "sts" => $this->sts,
            "pts" => $this->pts,
            "pts_type" => $this->pts_type,
            "sts_provided" => $this->sts_provided,
            "pts_provided" => $this->pts_provided,
            "car_key_quantity" => $this->car_key_quantity,
            "year" => $this->year,
            "milage" => $this->milage,
            "owner_number" => $this->owner_number,
            "color" => $this->color,
            "on_sale" => $this->on_sale,
            "favorite" => $this->favorite,
            "returned" => $this->returned,
            "price" => $this->price,
            "exterior_damage" => $this->exterior_damage,
            "interior_damage" => $this->interior_damage,
            "condition_electric" => $this->condition_electric,
            "condition_engine" => $this->condition_engine,
            "condition_gear" => $this->condition_gear,
            "condition_transmission" => $this->condition_transmission,
            "services" => $this->services,
            "car_additional" => $this->car_additional,
            "car_type" => new ModelResource($this->whenLoaded('car_type')),
            "car_mark_id" => $this->car_mark_id,
            "car_model_id" => $this->car_model_id,
            "car_generation_id" => $this->car_generation_id,
            "car_series_id" => $this->car_series_id,
            "car_modification_id" => $this->car_modification_id,
            "car_engine_id" => $this->car_engine_id,
            "car_transmission_id" => $this->car_transmission_id,
            "car_gear_id" => $this->car_gear_id,
            "internal_id" => $this->internal_id,
            "external_id" => $this->external_id,
            "arriving_method" => $this->arriving_method,
            "tow_truck_payment_id" => $this->tow_truck_payment_id,
            "parking" => new ModelResource($this->whenLoaded('parking')),
            "partner" => new ModelResource($this->whenLoaded('partner')),
            "presentation_contract_id" => $this->presentation_contract_id,
            "courier_fullname" => $this->courier_fullname,
            "courier_phone" => $this->courier_phone,
            "courier_type_id" => $this->courier_type_id,
            "client_id" => $this->client_id,
            "responsible_user_id" => $this->responsible_user_id,
            "parking_place_number" => $this->parking_place_number,
            "parking_car_sticker" => $this->parking_car_sticker,
            "parking_cost" => $this->parking_cost,
            "parked_days" => $this->parked_days,
            "status" => new ModelResource($this->whenLoaded('status')),
            "acceptedBy" => new ModelResource($this->whenLoaded('acceptedBy')),
            "created_user_id" => $this->created_user_id,
            "issued_by" => $this->issued_by,

        ];
    }
}
