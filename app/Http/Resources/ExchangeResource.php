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
            "spot"      => (boolean)$this->spot,
            "futures"   => (boolean)$this->futures,
            "is_active" => (boolean)$this->is_active,
        ];
    }
}
