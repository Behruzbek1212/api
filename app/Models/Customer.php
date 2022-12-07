<?php

namespace App\Models;

use App\Models\Chat\Chat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $about
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
        'about',
        'avatar',
        'balance',
        'owned_date',
        'location',
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
     * Update user data's
     *
     * @param Request $request
     */
    public function updateData(Request $request): void
    {
        $this->update([
            'name' => $request->get('customer')['name'],
            'about' => $request->get('customer')['about'],
            'location' => $request->get('customer')['location'],
            'address' => $request->get('customer')['address'],
            'owned_date' => $request->get('customer')['owned_date'],
        ]);
    }

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

    /**
     * Get chats list
     *
     * @return HasMany
     * @see https://laravel.com/docs/9.x/eloquent-relationships#one-to-many
     */
    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class);
    }
}
