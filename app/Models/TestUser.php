<?php

namespace App\Models;

//use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class TestUser extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'name',
        'surname',
        'sex',
        'position',
        'phone',
        'company_id',
        'test',
        'password'
    ];

    protected $casts = [
        'test'=>'array'
    ];
    protected $hidden = [
        'password',
    ];

    /**
     * Display the user information
     *
     * @return BelongsTo
     * @see https://laravel.com/docs/9.x/eloquent-relationships#one-to-one
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Customer::class,'company_id', 'id');
    }
}
