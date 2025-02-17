<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'profile_photo_url' => $this->profile_photo_url,
            'address' => $this->addresses,
            'profile' => new ProfileResource($this->profile),
            'addresses' => AddressResource::collection($this->addresses),
            'created_at' => $this->created_at->format('M j, Y'),
        ];
    }
}
