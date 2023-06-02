<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompaniesResource extends JsonResource
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
            'about' =>  $this->about ?? null,
            'active' =>  $this->active ?? null,
            'address'  =>  $this->address ?? null,
            'avatar' => $this->avatar ?? null,
            'name'  => $this->name ?? null,
            'jobs_count' =>  $this->jobs_count ?? null,
        ];
    }


}
