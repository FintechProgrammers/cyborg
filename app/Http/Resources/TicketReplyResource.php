<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketReplyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = null;

        if (!empty($this->admin)) {
            $user = $this->admin;
        } else {
            $user = $this->user;
        }

        return [
            'id'          => $this->uuid,
            'user_id'     => $user->uuid,
            'ticket_id'   => new TicketResource($this->ticket),
            'message'     => $this->reply,
            'file'        => $this->file_url,
            'created_at'   => $this->created_at
        ];
    }
}
