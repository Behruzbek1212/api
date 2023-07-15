<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WishlistCandidateResource extends JsonResource
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
            'title' => $this->title ?? null,
            'liked' => $this->liked ?? null,
            'location_id' => $this->location_id ?? null,
            'responded' => $this->responded ?? null,
            'slug' => $this->slug ?? null,
            'salary' => $this->salary ?? null,
            'work_type' => $this->work_type ?? null,
            'customer' => [
                'name' => $this->customer->name ?? null,
                'avatar' => $this->customer->avatar ?? null,
            ]
        ];
    }
}
