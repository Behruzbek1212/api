<?php

namespace App\Models\Bot;

use Illuminate\Database\Eloquent\Model;

class AdsonCrater extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bot_adson_craters';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'identification',
        'url'
    ];
}
