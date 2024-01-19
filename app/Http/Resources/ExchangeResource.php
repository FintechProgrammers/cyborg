<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExchangeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->uuid,
            'name'      => $this->name,
            "slug"      => $this->slug,
            "logo"      => $this->logo,
            "spot"      => (bool)$this->spot,
            "futures"   => (bool)$this->futures,
            "is_active" => (bool)$this->is_active,
            'is_binded' => isBinded($this->id),
        ];
    }
}
