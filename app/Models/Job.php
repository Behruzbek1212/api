<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string title
 * @property string $salary
 * @property string $type
 * @property array $requirements
 * @property array|null $tasks
 * @property array|null $advantages
 * @property integer $location_id
 * -------------- Relationships --------------
 * @property User[] $user
 */
class Job extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'title',
        'salary',
        'type',
        'requirements',
        'tasks',
        'advantages',
        'location_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'pivot',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'requirements' => 'array',
        'tasks' => 'array',
        'advantages' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Display the wishlist
     *
     * @return BelongsToMany
     * @see https://laravel.com/docs/9.x/eloquent-relationships#many-to-many
     */
    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }

    /**
     * Check if user saved wishlist
     *
     * @param User $user
     * @return bool
     */
    public function liked(User $user): bool
    {
        foreach ($this->user as $likedUser) {
            if ($likedUser->id === $user->id) {
                return true;
            }
        }

        return false;
    }
}
