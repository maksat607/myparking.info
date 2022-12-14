<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public static $wrap = null;

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
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->whenLoaded('roles', function () {
                return $this->roles->first()->name;
            }),
            'token' => $this->when(($this->token), function () {
                return $this->token;
            }),

        ];

//        "id": 19,
//    "name": "maksat",
//    "email": "maksat607@gmail.com",
//    "phone": "+7 (312) 659-47-40",
//    "email_verified_at": "2022-09-26T17:46:18.000000Z",
//    "parent_id": null,
//    "status": 1,
//    "created_at": "2022-09-26T17:46:17.000000Z",
//    "updated_at": "2022-09-26T17:46:18.000000Z",
//    "roles": [
//        {
//            "id": 2,
//            "name": "Admin",
//            "guard_name": "web",
//            "created_at": "2022-05-26T03:46:49.000000Z",
//            "updated_at": "2022-05-26T03:46:49.000000Z",
//            "pivot": {
//            "model_id": 19,
//                "role_id": 2,
//                "model_type": "App\\Models\\User"
//            }
//        }
//    ]
    }
}
