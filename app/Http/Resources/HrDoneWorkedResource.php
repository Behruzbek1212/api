<?php

namespace App\Http\Resources;

use App\Models\Transaction;
use App\Models\TransactionHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HrDoneWorkedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {  
       
        return [
            'hr' =>[ 
                'id' => $this->resource['statis']['hr']['id'] ?? null,
                'name' => $this->resource['statis']['hr']['fio'] ?? null,
                'subrole' => $this->resource['statis']['hr']['subrole'] ?? null,
                'candidate' => $this->resource['statis']['candidates'] ?? null,
                'comment' => $this->resource['statis']['comments'] ?? null,
                'interviews' => $this->resource['statis']['called_interviews'] ?? null,
                'resume' => $this->resource['statis']['resume'] ?? null,
                ],
            'hrHistory' => [
                 'data' => HistoryResource::collection($this->resource['history']),
                 'pagination' => [
                    'total' => $this->resource['history']->total() ?? null,
                    'per_page' => $this->resource['history']->perPage() ?? null,
                    'current_page' => $this->resource['history']->currentPage() ?? null,
                    'last_page' => $this->resource['history']->lastPage() ?? null,
                ] ?? null,
            ] 
        ];
    }
}
