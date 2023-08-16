<?php

namespace App\Models;

use App\Models\Chat\Chat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class CustomerChatComment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = 
    [
        'chat_id',
        'customer_id',
        'comment'
    ];


    public function chats():BelongsTo 
    {
        return $this->belongsTo(Chat::class);
    }


    public function customer():BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
