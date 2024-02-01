<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $feeBalance = !empty($this->fee) ? $this->fee : 0.00;
        $balance = !empty($this->balance) ? $this->balance : 0.00;

        return [
            'main_balance' => $balance,
            'fee_balance' => $feeBalance
        ];
    }
}
