<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TraficResource extends JsonResource
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
            'image' =>  $this->image ?? null,
            'name' =>  $this->name ?? null,
            'price'  =>  $this->price ?? null,
            'title' => $this->title ?? null,
            'description'  => $this->description ?? null,
            'top_day' =>  $this->top_day ?? null,
            'count_rise' =>  $this->count_rise ?? null,
            'vip_day' =>  $this->vip_day ?? null,
            'type' =>  $this->type ?? null,
            'status' =>  $this->status ?? null,
            'created_at' => formatDateTime($this->created_at) ?? null,
        ];
    }
}
