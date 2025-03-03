<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->resource->id,
            "name" => $this->resource->name,
            "email" => $this->resource->email,
            "phone" => $this->resource->phone,
            "position" => $this->resource->position->name,
            "position_id" => $this->resource->position_id,
            "registration_timestamp" => $this->resource->created_at?->getTimestamp(),
            "photo" => $this->resource->photo
        ];
    }
}
