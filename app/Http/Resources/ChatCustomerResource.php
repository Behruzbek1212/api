<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatCustomerResource extends JsonResource
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
            'customer_id' => $this->customer_id ?? null,
            'status' => $this->status, 
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,        
            'customer' => [
                'id' => $this->customer->id ?? null,
                'name' => $this->customer->name ?? null,
                'avatar'=> $this->customer->avatar ?? null,
                'location'=> $this->customer->location ?? null,
                'address'=> $this->customer->address ?? null,
            ],
            'job'=> [
                'id' => $this->job->id,
                'title' => $this->job->title ?? null,
                'slug' => $this->job->slug ?? null,
                'status' => $this->job->status ?? null,
                'work_hours' => $this->job->work_hours ?? null,
                'work_type' => $this->job->work_type ?? null,
                'location_id' => $this->job->location_id ?? null,
                'responded'=> $this->job->responded ?? null,
                'created_at'=> $this->job->created_at ,
                'updated_at'=> $this->job->updated_at,
                
            ]
            
        ];
    }


}

