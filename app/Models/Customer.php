<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property int $balance
 * @property string $address
 * @property bool $active
 *
 * @property User $user
 */
class Customer extends Model
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
        'name',
        'balance',
        'owned_date',
        'address',
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
        'owned_date' => 'datetime',
    ];

    /**
     * Display the user information
     *
     * @return BelongsTo
     * @see https://laravel.com/docs/9.x/eloquent-relationships#one-to-one
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Display the resumes
     *
     * @return HasMany
     * @see https://laravel.com/docs/9.x/eloquent-relationships#one-to-many
     */
    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }
}
