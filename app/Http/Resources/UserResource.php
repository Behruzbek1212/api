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
            "phone"=> $this->phone ?? null,
            "phone_verified_at"=> $this->phone_verified_at ?? null,
            "email"=> $this->email ?? null,
            "email_verified_at"=>$this->email_verified_at ?? null ,
            "role"=> $this->role ?? null,
            "balance"=> $this->balance ?? null,
            "verified"=> $this->verified ?? null,
            "created_at"=> $this->created_at ?? null,
            "updated_at"=>$this->updated_at ?? null,
            "candidate" => $this->candidate ?? null,
            "customer" => [
                "id" => $this->customer->id ?? null,
                "avatar" => $this->customer->avatar ?? null,
                "name" => $this->customer->name ?? null,
                "about" => $this->customer->about ?? null,
                "services" => $this->customer->services ?? null,
                "balance" => $this->customer->balance ?? 0 ,
                "owned_date" => $this->customer->owned_date ?? null,
                "location" => $this->customer->location ?? null,
                "address" => $this->customer->address ?? null,
                "active" => $this->customer->active ?? null,
                "created_at" => $this->customer->created_at ?? null,
                "updated_at" => $this->customer->updated_at ?? null,
                "deleted_at" => $this->customer->deleted_at ?? null,
                "status" => $this->getStatus($this->customer->id ?? null) ?? null,
            ]
        ];
    }

    public function getStatus($id) {
        if($id !== null ){
            $status = CustomerStatus::where('customer_id', $id)->orWhere('customer_id', null)->get();
            return $status;
        } 
        return [];
    }



}
