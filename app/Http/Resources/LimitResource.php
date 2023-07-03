<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LimitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'day' =>  $this->day ?? null,
            'price'  =>  $this->price ?? null,
            'condidate_limit' => $this->condidate_limit ?? null,
            'code'  => $this->code ?? null,
            'created_at' => formatDateTime($this->created_at) ?? null,
        ];
    }
}
