<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'today_profit'  =>  empty($this->today_profit) ? $this->today_profit : 0.00 . 'USDT',
            'total_profit'  =>  empty($this->total_profit) ? $this->total_profit : 0.00 . 'USDT'
        ];
    }
}
