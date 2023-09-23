<?php

namespace App\Repository;

use App\Models\Chat\Chat;
use Closure;

class ChatsRepository
{
    public static function getInctance(): ChatsRepository
    {
        return new static();
    }

    public function list()
    {
        $user = _auth()->user();
        if($user->role == 'customer'){
            return $user->customer->chats();
        } 
        if($user->role == 'candidate'){
            return $user->candidate->chats();
        }
    }

}
