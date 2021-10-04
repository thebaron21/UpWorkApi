<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
              "id" => $this->id,
              "name" => $this->name,
              "email" => $this->email,
              "phone_number" => $this->phone_number,
              "address" => $this->address,
              "image" => url('/') . '/storage/'. $this->image,
              "slug_skills" =>  $this->slug_skills,
              "created_at" => $this->create_at,
              "updated_at" => $this->updated_at
        ];
    }
}
