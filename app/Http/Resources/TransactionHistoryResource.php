<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,
            'trafic_id' => $this->trafic->id ?? null,
            'user_id' => $this->user->id ?? null,
            'name' => $this->user->name ?? null,
            'trafic_name' => $this->trafic->name ?? null,
            'used_balance' => $this->amount ?? null,
            'created_at' => formatDateTime($this->created_at) ?? null,
        ];
    }
}
