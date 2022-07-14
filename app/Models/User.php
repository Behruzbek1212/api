<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\Contracts\HasApiTokens as ContractsHasApiTokens;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, ContractsHasApiTokens
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'role',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verified' => 'boolean',
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    /**
     * Check if the phone number is exist.
     * 
     * @return bool
     */
    public function existPhone()
    {
        return !is_null($this->phone);
    }

    /**
     * Check if the email address is exist.
     * 
     * @return bool
     */
    public function existEmail()
    {
        return !is_null($this->email);
    }

    /**
     * Mutate the phone number
     * 
     * @param string $value
     * @return void
     */
    public function setPhoneAttribute($value)
    {
        $expression =
            "/^[\+]?([0-9]{3})?[-\(\s\.]?([0-9]{2})[-\)\s\.]?([0-9]{7})$/";

        $value = preg_replace($expression, '$1$2$3', $value);
        $this->attributes['phone'] = $value;
    }
}
