<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WishlistCustomerResource extends JsonResource
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
            'avatar' => $this->avatar ?? null,
            'name' => $this->name ?? null,
            'surname' => $this->surname ?? null,
            'sex' => $this->sex ?? null,
            'spheres' => $this->spheres ?? null,
            'education_level' => $this->education_level ?? null,
            'languages' => $this->languages ?? null,
            'specialization' => $this->specialization ?? null,
            'birthday' => $this->birthday ?? null,
            'address' => $this->address ?? null,
            'services' => $this->services ?? null,
            'test' => $this->test ?? null,
            'active' => $this->active ?? null,
            '__comment' => $this->__comment ?? null,
            '__conversation' => $this->__conversation ?? null,
            '__conversation_date' => $this->__conversation_date ?? null,
            'created_at' => $this->created_at ?? null,
            'updated_at' => $this->updated_at ?? null,
            'deleted_at' => $this->deleted_at ?? null,
            'user' => $this->user ?? null,
            'pivot' => $this->pivot ?? null
        ];
    }
}
