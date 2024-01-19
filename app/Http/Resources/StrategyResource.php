<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StrategyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->uuid,
            'market'         => new MarketResource($this->market),
            'market_type'    => $this->trade_type,
            'strategy_mode'  => $this->strategy_mode,
            'stop_loss'     => $this->stop_loss . '%',
            'take_profit'  => $this->take_profit . '%'
        ];
    }

}
