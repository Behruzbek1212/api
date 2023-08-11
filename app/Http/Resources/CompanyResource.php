<?php

namespace App\Http\Resources;

use App\Models\Transaction;
use App\Models\TransactionHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'user_id' => $this->user->id,
            'avatar' => $this->avatar ?? null,
            'name'  => $this->name ?? null,
            'about' =>  $this->about ?? null,
            'balance' =>  $this->balance ?? null,
            'location'  => $this->location ?? null,
            'address'  =>  $this->address ?? null,
            'active' =>  $this->active ?? null,
            'jobs_count' =>  $this->jobs_count ?? null,
            // 'total_balance' => $this->transation() ?? 0 // campaniya tuldirgan balansi
        ];
    }
}
