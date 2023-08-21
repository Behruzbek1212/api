<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalledInterviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
     
        return [
            'id' => $this->id ?? null,
            'candidate_id' => $this->candidate_id ?? null,
            'user_id' => $this->user_id ?? null,
            'date' => $this->date ?? null,
            'status' => $this->status ?? null,
            'created_at' => $this->created_at ?? null,
            'updated_at' => $this->updated_at ?? null,
            'candidate' => [
                'id' => $this->candidate->id ?? null,
                'avatar' => $this->candidate->avatar ?? null,
                'name' => $this->candidate->name ?? null,
                'surname' => $this->candidate->surname ?? null,
                'specialization' => $this->candidate->specialization ?? null
            ],
            'user' => [
                'id' => $this->user->id ?? null,
                'fio' => $this->user->fio ?? null,
                'subrole' => $this->user->subrole ?? null
            ]
        ];
    }

    

    
}
