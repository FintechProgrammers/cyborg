<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $settings = json_decode($this->settings);

        $trade_Values = json_decode($this->trade_values);

        return [
            'id'            => $this->uuid,
            'market'        => new MarketResource($this->market),
            'exchange'      => new ExchangeResource($this->exchange),
            'started'       => (bool)$this->started,
            'running'       => (bool)$this->running,
            'strategy_mode' => $this->strategy_mode,
            'trade_type'    => $this->trade_type,
            'settigs'       => [
                'stop_loss'     => $settings->stop_loss ? $settings->stop_loss : null,
                'take_profit'   => $settings->take_profit ? $settings->take_profit : null,
                'capital'       => $settings->capital ? $settings->capital : null,
                'first_buy'     => $settings->first_buy ? $settings->first_buy : null,
                'margin_limit'  => $settings->margin_limit ? $settings->margin_limit : null,
                'm_ratio'       => $settings->m_ratio ? $settings->m_ratio : null,
                'price_drop'    => $settings->price_drop ? $settings->price_drop : null,
            ],
            'trade_values' => [
                'position_amount'   => !empty($trade_Values->position_amount) ? $trade_Values->position_amount : 0,
                'in_position'       => !empty($trade_Values->in_position) ? (bool) $trade_Values->in_position : false,
                'buy_position'      => !empty($trade_Values->buy_position) ? (bool) $trade_Values->buy_position : false,
                'sell_position'     => !empty($trade_Values->sell_position) ? (bool) $trade_Values->sell_position : false,
                'margin_calls'      => !empty($trade_Values->margin_calls) ? $trade_Values->margin_calls : 0,
                'floating_loss'     => !empty($trade_Values->floating_loss) ? $trade_Values->floating_loss : 0,
                'trade_price'       => !empty($trade_Values->trade_price) ? $trade_Values->trade_price : 0,
                'quantity'          => !empty($trade_Values->quantity) ? $trade_Values->quantity : 0,
                'profit'            => !empty($trade_Values->profit) ? $trade_Values->profit : 0,
            ],
            'is_copied'      => (bool) $this->is_copied,
        ];
    }
}
