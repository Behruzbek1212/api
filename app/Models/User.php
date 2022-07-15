<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\Contracts\HasApiTokens as ContractsHasApiTokens;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property integer $id
 * @property integer $phone
 * @property boolean $verified
 * @property string $name
 * @property string $role
 * @property string $email
 * @property string $password
 * @property string $phone_verified_at
 * @property string $email_verified_at
 * -------------- Relationships --------------
 * @property Job[] $wishlist
 */
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
    public function existPhone(): bool
    {
        return !is_null($this->phone);
    }

    /**
     * Check if the email address is exist.
     *
     * @return bool
     */
    public function existEmail(): bool
    {
        return !is_null($this->email);
    }

    /**
     * Mutate the phone number
     *
     * @param string $value
     * @return void
     */
    public function setPhoneAttribute(string $value): void
    {
        $expression =
            "/^\+?(\d{3})?[-\s]?(\d{2})[-\s]?(\d{7})$/";

        $value = preg_replace($expression, '$1$2$3', $value);
        $this->attributes['phone'] = $value;
    }

    /**
     * Display the wishlist
     *
     * @return BelongsToMany
     * @see https://laravel.com/docs/9.x/eloquent-relationships#many-to-many
     */
    public function wishlist(): BelongsToMany
    {
        return $this->belongsToMany(Job::class, 'wishlists');
    }
}
