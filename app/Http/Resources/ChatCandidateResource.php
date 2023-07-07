<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatCandidateResource extends JsonResource
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
            'job_slug' => $this->job_slug,
            'candidate_id' => $this->candidate_id,
            'status' => $this->status, 
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,        
            'candidate' => [
                'name' => $this->candidate->name ?? null,
                'surname'=> $this->candidate->surname ?? null,
                'avatar'=> $this->candidate->avatar ?? null,
                'birthday'=> $this->candidate->birthday ?? null,
                'active' => $this->candidate->active ?? null,
                'specialization' => $this->candidate->specialization ?? null,

            ],
            'resume' => [
                'id'=> $this->resume->id,
                'status' => $this->status,
                'experience'=> $this->experience,
                'data' => [
                    'position' => $this->resume->data['position'] ?? null,
                     'status' => $this->resume->data['status'] ?? null
                ]
            ]
        ];
    }


}
