<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordVerification extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'password_verification';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'phone',
        'email',
        'token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
