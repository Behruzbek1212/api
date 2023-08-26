<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestResult extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'result' => 'array',
    ];


    /**
     * Display the candidate
     *
     * @return BelongsTo
     * @see https://laravel.com/docs/10.x/eloquent-relationships#one-to-many-inverse
     */

    public function candidate():BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Display the customer
     *
     * @return BelongsTo
     * @see https://laravel.com/docs/10.x/eloquent-relationships#one-to-many-inverse
     */

    public function customer():BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
