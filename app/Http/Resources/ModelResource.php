<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ModelResource extends JsonResource
{
    public static $codes = [
        'storage' => 'Х',
        'issued' => 'В',
        'draft' => 'Ч',
        'pending' => 'НХ',
        'denied-for-storage' => 'ОХ',
        'cancelled-by-partner' => 'ОП',
        'cancelled-by-us' => 'ОН',
        'deleted' => 'УН'
    ];
    public static $model;

    public function __construct($resource)
    {
        self::withoutWrapping();
        parent::__construct($resource);
    }

    public static function collection($resource, $model = null)
    {
        self::$model = $model;
        return parent::collection($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->when(isset($this->id), function () {
                return $this->id;
            }),
            'name' => $this->when(isset($this->name), function () {
                return $this->name;
            }),
            'shortname' => $this->when(isset($this->shortname), function () {
                return $this->shortname;
            }),
            'code' => $this->when(isset($this->code), function () {
                return self::$model === 'status'
                    ? self::$codes[$this->code]
                    : $this->code;
            }),
            'title' => $this->when(isset($this->title), function () {
                return $this->title;
            }),
            'vin' => $this->when(isset($this->vin), function () {
                return $this->vin;
            }),
            'license_plate' => $this->when(isset($this->license_plate), function () {
                return $this->license_plate;
            }),
            'status' => $this->when(isset($this->status), function () {
                return new $this($this->status);
            }),
            'email' => $this->when(isset($this->email), function () {
                return ($this->email);
            }),
            'client_id' => $this->when(isset($this->client_id), function () {
                return ($this->client_id);
            }),
            'is_issue' => $this->when(isset($this->is_issue), function () {
                return ($this->is_issue);
            }),
            'arriving_interval' => $this->when(isset($this->formated_arriving_interval), function () {
                return ($this->formated_arriving_interval);
            }),
            'arriving_at' => $this->when(isset($this->formated_arriving_at), function () {
                return ($this->formated_arriving_at);
            }),
            'application' => $this->when(isset($this->application), function () {
                return  new ApplicationResource($this->application);
            }),

        ];

    }
}
