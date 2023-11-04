<?php

namespace App\Http\Resources\ExamAdmin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" =>  $this->id ?? null,
            "name"=>  $this->name ?? null,
            "key"=> $this->key ?? null,
            "title"=> $this->title ?? null,
            "image"=> $this->image ?? null,
            "datetime_start"=> $this->datetime_start ?? null,
            "datetime_end"=> $this->datetime_end ?? null,
            "max_ball"=> $this->max_ball ?? null,
            "attemps_count"=> $this->attemps_count ?? 0,
            "duration"=> $this->duration ?? null,
            "questions_count"=> $this->questions_count ?? null,
            "status"=> $this->status ?? null,
            "created_at"=> $this->created_at ?? null,
            "updated_at"=> $this->updated_at ?? null,
        ];
    }
}
