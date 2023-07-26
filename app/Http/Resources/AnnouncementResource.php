<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
     
        return [
            'title' => $this->title ?? null,
            'salary' => $this->salary ?? null,
            'work_hours' => $this->work_hours ?? null,
         
            'job-slug' => $this->slug ?? null,
            'image' => $this->image ?? null,
            'for-connection' => $this->customer->user->phone ?? null,
            'post_number' => null,
            'company_name' => $this->customer->name  ?? null
        ];
    }

    

    
}
