<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dd($this->user->resumes);
        return [
            'id' => $this->id ?? null,
            'avatar' => $this->avatar ?? null,
            'name' => $this->name ?? null,
            'surname' => $this->surname ?? null,
            'sex' => $this->sex ?? null,
            'spheres' => $this->spheres ?? null,
            'education_level' =>  $this->education_level ?? null,
            "languages" => $this->languages ?? null,
            "specialization" => $this->specialization ?? null,
            "experience" => $this->resume_experence() ?? null,
            "birthday" => $this->birthday ?? null,
            "address" => $this->address ?? null,
            "services" => $this->services ?? null,
            "test" => $this->test ?? null,
            "active" => $this->active ?? null,
            "category_id" => $this->category_id ?? null,
            "status" => $this->status ?? null,
            "user" => $this->user() ?? []
        ];
    }

    public function user()
    {
        $limit_start_day =  user()->customer->limit_start_day;
        $limit_end_day =  user()->customer->limit_end_day;
        if ($limit_start_day < date('Y-m-d H:i:s') && $limit_end_day > date('Y-m-d H:i:s')) {
            return [
                'id' => $this->user->id ?? null,
                'email' => $this->user->email ?? null,
                'phone' => $this->user->phone ?? null,
                'resume' => $this->resumes() ?? [],
            ];
        }
        return [];
    }

    public function resumes()
    {
        foreach ($this->user->resumes as  $resume) {
            return [
                'id' => $resume->id ?? null,
                'user_id' => $resume->user_id ?? null,
                'downloads' => $resume->downloads ?? null,
                'visits' => $resume->visits ?? null,
                'data' => $resume->data ?? null,
            ];
        }
    }

    public function resume_experence()
    {
        foreach ($this->user->resumes as  $resume) {
            return $resume->calculate_experience($resume->data) ?? null;
        }
    }
}
