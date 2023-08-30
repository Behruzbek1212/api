<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestResultResource extends JsonResource
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
            'candidate_id' => $this->candidate_id ?? null,
            'customer_id' => $this->customer_id ?? null,
            'result' => $this->result ?? null,
            'candidate' => [
                'id' => $this->candidate->id ?? null,
                'name' => $this->candidate->name ?? null,
                'surname' => $this->candidate->surname ?? null,
                'avatar' => $this->candidate->avatar ?? null,
                'specialization' => $this->candidate->specialization ?? null,
                'birthday' => $this->candidate->birthday ?? null,
            ],
            'created_at' => $this->created_at ?? null,
            'updated_at' => $this->updated_at ?? null
        ];
    }


    
}
