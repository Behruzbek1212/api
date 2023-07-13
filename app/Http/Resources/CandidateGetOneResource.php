<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateGetOneResource extends JsonResource
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
            'education_level' =>  $this->education_level ?? null,
            "languages" => $this->languages ?? null,
            "experience" => $this->resume_experience() ?? null,
            "specialization" => $this->specialization ?? null,
            "birthday" => $this->birthday ?? null,
            "address" => $this->address ?? null,
            "services" => $this->services ?? null,
            "test" => $this->test ?? null,
            "active" => $this->active ?? null,
            "category_id" => $this->category_id ?? null,
            "status" => $this->status ?? null,
            "user" => $this->user() ?? [],
            "experience" => $this->resume_experience() ?? null
        ];
    }

    public function user()
    {
        return [
            'id' => $this->user->id ?? null,
            'email' => $this->user->email ?? null,
            'phone' => $this->user->phone ?? null,
            'resume' => $this->resumes() ?? [],
        ];
    }

    public function resumes()
    {
        foreach ($this->user->resumes ?? [] as  $resume) {
            return [
                'id' => $resume->id ?? null,
                'user_id' => $resume->user_id ?? null,
                'downloads' => $resume->downloads ?? null,
                'visits' => $resume->visits ?? null,
                'data' => $resume->data ?? null,
            ];
        }
    }

    public function resume_experience()
    {
        foreach ($this->user->resumes ?? [] as  $resume) {
            return $resume->calculate_experience($resume->data) ?? null;
        }
    }
}
