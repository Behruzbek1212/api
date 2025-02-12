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
            'job_title' => $this->job->title ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,        
            'candidate' => [
                'name' => $this->candidate->name ?? null,
                'surname'=> $this->candidate->surname ?? null,
                'avatar'=> $this->candidate->avatar ?? null,
                'birthday'=> $this->candidate->birthday ?? null,
                'active' => $this->candidate->active ?? null,
                'address' => $this->candidate->address ?? null,
                'specialization' => $this->candidate->specialization ?? null,

            ],
            'resume' => $this->resume ?  [
                'id'=> $this->resume->id,
                'experience'=> $this->resume->experience ?? null,
                'data' => [
                    'position' => $this->resume->data['position'] ?? null,
                     'status' => $this->resume->data['status'] ?? null
                ]
            ] : null,
        
        ];
    }


}
