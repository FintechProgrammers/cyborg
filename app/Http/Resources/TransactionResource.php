<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'reference'     => $this->reference,
            'coin'          => $this->coin,
            'amount'        => $this->amount . ' ' . $this->coin,
            'type'          => $this->type,
            'action'        => $this->action,
            'status'        => $this->status,
            'narration'     => $this->narration,
            'created_at'    => $this->created_at
        ];
    }
}
