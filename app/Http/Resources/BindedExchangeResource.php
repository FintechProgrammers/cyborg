<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BindedExchangeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->uuid,
            'spot_balance'      => $this->spot_balance,
            'future_balance'    => $this->future_balance,
            'is_binded'         => (bool) $this->is_binded,
            'exchange'          => new ExchangeResource($this->exchange),
            'created_at'        => $this->created_at
        ];
    }
}
