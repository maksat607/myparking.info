<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ModelResource extends JsonResource
{

    public function __construct($resource)
    {
        self::withoutWrapping();
        parent::__construct($resource);
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
            'id' => $this->id,
            'name' => $this->when(($this->name), function () {
                return $this->name;
            }),
            'shortname' => $this->when(($this->shortname), function () {
                return $this->shortname;
            }),
            'code' => $this->when(($this->code), function () {
                return $this->code;
            }),
            'title' => $this->when(($this->title), function () {
                return $this->title;
            }),
        ];
    }
}
