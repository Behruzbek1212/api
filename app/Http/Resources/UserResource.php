<?php

namespace App\Http\Resources;

use App\Models\Customer;
use App\Models\CustomerStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "phone" => $this->phone ?? null,
            "phone_verified_at" => $this->phone_verified_at ?? null,
            "email" => $this->email ?? null,
            "email_verified_at" => $this->email_verified_at ?? null,
            "role" => $this->role ?? null,
            "balance" => $this->balance ?? null,
            "verified" => $this->verified ?? null,
            "created_at" => $this->created_at ?? null,
            "updated_at" => $this->updated_at ?? null,
            "candidate" => $this->candidate ?? null,
            "subrole" => $this->subrole ?? null,
            "fio" => $this->fio ?? null,
            "customer_id" => $this->customer_id ?? null,
            "customer" => [
                "id" => $this->customer->id ?? null,
                "avatar" => $this->customer->avatar ?? null,
                "name" => $this->customer->name ?? null,
                "about" => $this->customer->about ?? null,
                "services" => $this->customer->services ?? null,
                "balance" => $this->customer->balance ?? 0,
                "owned_date" => $this->customer->owned_date ?? null,
                "location" => $this->customer->location ?? null,
                "address" => $this->customer->address ?? null,
                "active" => $this->customer->active ?? null,
                "created_at" => $this->customer->created_at ?? null,
                "updated_at" => $this->customer->updated_at ?? null,
                "deleted_at" => $this->customer->deleted_at ?? null,
                "status" => $this->getStatus($this->customer->id ?? null) ?? null,
            ],
            "trafics" => $this->getTrafics() ?? null
        ];
    }

    public function getStatus($id)
    {
        if ($id !== null) {
            $status = CustomerStatus::where('customer_id', $id)->orWhere('customer_id', null)->get();
            return $status;
        }
        return [];
    }



    public function getTrafics()
    {
        $list = [];
        foreach ($this->transaction_histories ?? [] as $receiver) {
            $list[] = [
                'id' => $receiver->id,
                'service_id' => $receiver->service_id,
                'service_count' => $receiver->service_count,
                'service_sum' => $receiver->service_sum,
                'service_name' => $receiver->service_name,
                'started_at' => $receiver->started_at,
                'expire_at' => $receiver->expire_at,
                'key' => $receiver->key,
            ];
        }
        return  $list;
    }
}
