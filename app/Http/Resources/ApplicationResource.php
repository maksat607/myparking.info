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
            "id" => $this->id  ?? null,
            "car_title" => $this->car_title ?? null,
            "vin" => $this->vin ?? null,
            "license_plate" => $this->license_plate ?? null,
            "sts" => $this->sts ?? null,
            "pts" => $this->pts ?? null,
            "pts_type" => $this->pts_type ?? null,
            "sts_provided" => $this->sts_provided ?? null,
            "pts_provided" => $this->pts_provided ?? null,
            "car_key_quantity" => $this->car_key_quantity ?? null,
            "year" => $this->year ?? null,
            "milage" => $this->milage ?? null,
            "owner_number" => $this->owner_number ?? null,
            "color" => $this->color ?? null,
            "on_sale" => $this->on_sale ?? null,
            "favorite" => $this->favorite ?? null,
            "returned" => $this->returned ?? null,
            "price" => $this->price ?? null,
            "exterior_damage" => $this->exterior_damage ?? null,
            "interior_damage" => $this->interior_damage ?? null,
            "condition_electric" => $this->condition_electric ?? null,
            "condition_engine" => $this->condition_engine ?? null,
            "condition_gear" => $this->condition_gear ?? null,
            "condition_transmission" => $this->condition_transmission ?? null,
            "services" => $this->services ?? null,
            "car_additional" => $this->car_additional ?? null,
            "car_type_id" => $this->car_type_id ?? null,
            "car_mark_id" => $this->car_mark_id ?? null,
            "car_model_id" => $this->car_model_id ?? null,
            "car_generation_id" => $this->car_generation_id ?? null,
            "car_series_id" => $this->car_series_id ?? null,
            "car_modification_id" => $this->car_modification_id ?? null,
            "car_engine_id" => $this->car_engine_id ?? null,
            "car_transmission_id" => $this->car_transmission_id ?? null,
            "car_gear_id" => $this->car_gear_id ?? null,
            "internal_id" => $this->internal_id ?? null,
            "external_id" => $this->external_id ?? null,
            "arriving_method" => $this->arriving_method ?? null,
            "tow_truck_payment_id" => $this->tow_truck_payment_id ?? null,
            "parking" => $this->parking_id ?? null,
            "partner" => $this->partner_id ?? null,
            "presentation_contract_id" => $this->presentation_contract_id ?? null,
            "courier_fullname" => $this->courier_fullname ?? null,
            "courier_phone" => $this->courier_phone ?? null,
            "courier_type_id" => $this->courier_type_id ?? null,
            "client_id" => $this->client_id ?? null,
            "responsible_user_id" => $this->responsible_user_id ?? null,
            "parking_place_number" => $this->parking_place_number ?? null,
            "parking_car_sticker" => $this->parking_car_sticker ?? null,
            "parking_cost" => $this->parking_cost ?? null,
            "parked_days" => $this->parked_days ?? null,
            "status_id" => $this->status_id ?? null,
            "acceptedBy" => $this->accepted_by ?? null,
            "created_user_id" => $this->created_user_id ?? null,
            "issued_by" => $this->issued_by ?? null,
            "car_type" => new ModelResource($this->whenLoaded('car_type')),
            "parking" => new ModelResource($this->whenLoaded('parking')),
            "partner" => new ModelResource($this->whenLoaded('partner')),
            "status" => new ModelResource($this->whenLoaded('status')),
            "acceptedBy" => new ModelResource($this->whenLoaded('acceptedBy')),
            "attachments" => ($this->whenLoaded('attachments')),
            "issuance" => $this->whenLoaded('issuance'),
        ];
    }
}
