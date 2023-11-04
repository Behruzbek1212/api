<?php

namespace App\Http\Resources\ExamAdmin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamQuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {  
       
        return [
            'id' => $this['id'] ?? null,
            'exam_id' => $this['exam_id'] ?? null,
            "questions"=> [
                    "id"=> $this['questions'][0]['id'] ?? null,
                    "question"=> $this['questions'][0]['question'] ?? null,
                    "image"=> $this['questions'][0]['image'] ?? null,
                    "video"=> $this['questions'][0]['video'] ?? null,
                    "position"=> $this['questions'][0]['position'] ?? null,
            ] ?? null,
        ];
    }
}
