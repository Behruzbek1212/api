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
            'user_id' => $this->user->id ?? null,
            'service_id' => $this->service_id ?? null,
            'service_sum' => $this->service_sum ?? null,
            'service_name' => $this->service_name ?? null,
            'started_at' => formatDateTime($this->started_at) ?? null,
            'expire_at' => formatDateTime($this->expire_at) ?? null,
            'created_at' => formatDateTime($this->created_at) ?? null,
            'key' => $this->key ?? null,
        ];
    }
}
