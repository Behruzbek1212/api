<?php

namespace App\Http\Resources;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateExamResource extends JsonResource
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
            'candidate_id' => $this->candidate->id,
            'customer_id' => $this->customer->id,
            'candidate_name' => $this->candidate->name . ' ' . $this->candidate->surname,
            'customer_name' => $this->customer->name,
            // 'exams' => $this->exams(),

        ];
    }

    public function exams()
    {
        foreach ($this->exams ?? [] as  $exam) {
            return [
                'id' => $exam->id ?? null,
            ];
        }
    }
}
