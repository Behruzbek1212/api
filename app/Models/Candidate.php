<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property User $user
 */
class Candidate extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'avatar',
        'name',
        'surname',
        'spheres',
        'birthday',
        'address',
        'specialization',
        'active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'user_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'spheres' => 'array',
        'birthday' => 'datetime',
    ];

    /**
     * Set user avatar mutation
     *
     * @return Attribute
     */
    public function avatar(): Attribute
    {
        $default_avatar = 'https://static.jobo.uz/img/default.webp';

        return Attribute::make(
            set: fn ($value) =>
                is_null($value) ? $default_avatar : $value
        );
    }

    /**
     * Set user avatar mutation
     *
     * @return Attribute
     */
    public function phone(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user->phone,
            set: fn ($value) =>
                $this->user()->update([ 'phone' => $value ])
        );
    }

    /**
     * Set user avatar mutation
     *
     * @return Attribute
     */
    public function email(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user->email,
            set: fn ($value) =>
            $this->user()->update([ 'email' => $value ])
        );
    }

    /**
     * Display the user information
     *
     * @return BelongsTo
     * @see https://laravel.com/docs/9.x/eloquent-relationships#one-to-one-defining-the-inverse-of-the-relationship
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
