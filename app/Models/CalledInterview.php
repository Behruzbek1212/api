<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalledInterview extends Model
{
    use HasFactory;


    protected  $guarded = [];


    /**
     * Display the user information
     *
     * @return BelongsTo
     * @see https://laravel.com/docs/10.x/eloquent-relationships#one-to-one
     */

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Display the candidate information
     *
     * @return BelongsTo
     * @see https://laravel.com/docs/10.x/eloquent-relationships#one-to-one
     */
    public function candidate():BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
   
}
