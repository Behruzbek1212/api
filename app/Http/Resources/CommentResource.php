<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


        return [
             'name' =>$this->user->candidate->name ?? $this->user->customer->name ??   $this->user->phone ??  null,
             'role' => $this->user->role ?? null,
             'avatar' => $this->user->candidate->avatar ?? $this->user->customer->avatar ??  null,
             'body' => $this->body ?? null,
             'created_at' => $this->created_at
        ];
    }


}
