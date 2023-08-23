<?php

namespace App\Http\Resources;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'candidate_id' => $this->candidateName($this->properties['attributes']['candidate_id'] ?? $this->properties['attributes']['id'] ?? null)->id,
            'name' => $this->candidateName($this->properties['attributes']['candidate_id'] ?? $this->properties['attributes']['id'] ?? null)->name ?? null,
            'surname' => $this->candidateName($this->properties['attributes']['candidate_id'] ?? $this->properties['attributes']['id'] ?? null)->surname ?? null,
            'position' => $this->candidateName($this->properties['attributes']['candidate_id'] ?? $this->properties['attributes']['id'] ?? null)->avatar ?? null,
            'end_task' => $this->eventName($this->event, $this->log_name) ?? null,
            'comment' => $this->getComment($this ?? null) ?? null,
            'interview' => $this->getInterview($this ?? null) ?? null,
        ];
    }

    public function candidateName($candidate_id)
    {
        if($candidate_id !== null){
            $data = Candidate::query()->where('id', $candidate_id)->first();

            return $data;
        }
        return [];
        
    }

    public function eventName($eventName, $column)
    {
        $data = [];
        if ($data !== null) {
            if ($eventName == 'created' &&  $column == 'candidates') {
                $data['text'] = [
                    'uz' => 'Candidate kiriti',
                    'en' => 'Candidate created',
                    'ru' => 'Кандидат создан'
                ];
            }
            if ($eventName == 'created' &&  $column == 'comments') {
                $data['text'] = [
                    'uz' => 'Izoh yozdi',
                    'en' => 'He wrote a comment',
                    'ru' => 'Он написал комментарий'
                ];
            }
            if ($eventName == 'created' &&  $column == 'called_interviews') {
                $data['text'] = [
                    'uz' => 'Suhbatga chaqirdi',
                    'en' => 'He called for an interview',
                    'ru' => 'Он вызвал на собеседование'
                ];
            }
        }

        return $data;
    }

    public function getComment($data)
    {
        $comment = [];
        if ($data !== null) {
            if ($data->log_name == 'comments') {
                $comment['text'] = $data->properties['attributes']['body'];
                $comment['created_at'] = $data->properties['attributes']['created_at'];
            }
        }


        return $comment;
    }

    public function getInterview($data)
    {
        $interview = [];
        if ($data !== null) {
            if ($data->log_name == 'called_interviews') {
                $interview['datetime'] = $data->properties['attributes']['date'];
                $interview['status'] = $data->properties['attributes']['status'];
            }
        }


        return $interview;
    }
}
