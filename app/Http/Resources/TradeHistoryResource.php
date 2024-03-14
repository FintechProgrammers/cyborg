<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TradeHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->uuid,
            'exchange'      => new ExchangeResource($this->exchange),
            'market'        => $this->market,
            'trade_price'   => $this->trade_price,
            'quantity'      => $this->quantity,
            'trade_type'    => $this->trade_type,
            'profit'        => $this->profit,
            'type'          => $this->type,
            'is_profit'     => (bool) $this->is_profit,
            'is_stoploss'   => (bool) $this->is_stoploss,
            'created_at'    => $this->created_at,
        ];
    }
}
