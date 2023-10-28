<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReatingTestCandidateResource extends JsonResource
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
            'candidate_id' => $this->candidate_id ?? null,
            'name' => $this->candidate->name ?? null,
            'surname' => $this->candidate->surname ?? null,
            'languages' => $this->candidate->languages ?? null,
            'phone' => $this->candidate->user->phone ?? null,
            'resume' => [
                'id' => optional($this->candidate?->user?->resumes?->first())->id ?? null,
                'experience' => optional($this->candidate?->user?->resumes?->first())->experience ?? null,
                
            ] ?? [],
            'avatar' => $this->candidate->avatar ?? null,
            'specialization' => $this->candidate->specialization ?? null,
            'birthday' => $this->candidate->birthday ?? null,
            'percentage' => $this->percentage ?? null,
            'created_at' => $this->created_at ?? null,
            'updated_at' => $this->updated_at ?? null
        ];
    }
}
