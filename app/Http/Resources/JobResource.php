<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
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
            'title' =>  $this->title ?? null,
            "salary"=> $this->salary ?? null,
            "languages"=> $this->languages ?? null,
            "education_level"=> $this->education_level ?? null,
            "sphere"=> $this->sphere ?? null,
            "experience"=>$this->experience ?? null,
            "work_type"=> $this->work_type ?? null,
            "work_hours"=> $this->work_hours ?? null ,
            'for_communication_phone' => $this->for_communication_phone ?? null ,
            'for_communication_link' => $this->for_communication_link ?? null, 
            "about"=> $this->about ?? null,
            "location_id"=> $this->location_id ?? null,
            "category_id"=> $this->category_id ?? null,
            "slug"=> $this->slug ?? null,
            "status"=> $this->status ?? null,
            'liked' => $this->liked ?? null,
            'responded' => $this->responded ?? null,
            "created_at"=> $this->created_at ?? null,
            "updated_at"=> $this->updated_at ?? null,
            'customer' => [
                'name' => $this->customer->name ?? null,
                'avatar' => $this->customer->avatar ?? null,
            ],

        ];
    }


}