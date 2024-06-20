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
         if ($user->role == 'customer') {
               if ($user->customer) {
                   return $user->customer->chats();
                } else {
                   return [];
                 }
         } elseif ($user->role == 'candidate') {
                 if ($user->candidate) {
                     return $user->candidate->chats();
                 } else {
                     return [];
                 }
         } else {
             return [];
         }
    }

}
