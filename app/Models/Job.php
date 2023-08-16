<?php

namespace App\Models;

use App\Filters\Filterable;
use App\Models\Chat\Chat;
use App\Traits\ApiLogActivity;
use App\Traits\HasScopes;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Nette\Utils\Random;

/**
 * @property string title
 * @property string $salary
 * @property string $type
 * @property array $requirements
 * @property array|null $tasks
 * @property array|null $advantages
 * @property integer $location_id
 * @property bool $liked
 * -------------- Relationships --------------
 * @property Customer $customer
 */
class Job extends Model
{
    use Filterable;
    use HasFactory;
    use SoftDeletes;
    use HasScopes;
    use ApiLogActivity;
    // use LogsActivity;
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'slug';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'title',
        'salary',
        'languages',
        'education_level',
        'sphere',
        'about',
        'work_type',
        'experience',
        'location_id',
        'category_id',
        'slug',
        'status',
        'work_hours',
        'for_communication_phone',
        'for_communication_link',
        'trafic_id',
        'trafic_expired_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'liked',
        'responded'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'customer_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sphere' => 'array',
        'salary' => 'array',
        'languages' => 'array',
        'advantages' => 'array',
        'for_communication_link' => 'array',
        'for_communication_phone' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Display the customer information
     *
     * @return BelongsTo
     * @see https://laravel.com/docs/9.x/eloquent-relationships#one-to-many-inverse
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function trafic()
    {
        return $this->hasOne(Trafic::class, 'id', 'trafic_id');
    }


    public function customer_one()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    /**
     * Set slug attribute while creating new vacancy
     *
     * @return Attribute
     */
    public function slug(): Attribute
    {
        return Attribute::make(
            set: fn ($val, $attr) => is_null($val) ?
                Str::slug($attr['title']) . '-' . Random::generate('5', '0-9') : $val
        );
    }

    /**
     * Check if user saved wishlist
     *
     * @return User|BelongsToMany|null
     */
    public function getLikedByCurrentUser(): User|BelongsToMany|null
    {
        return $this
            ->belongsToMany(User::class, 'wishlists')
            ->where('user_id', @_auth()->id() ?? 0)
            ->first();
    }

    /**
     * Check if user responded to vacancy
     *
     * @return bool
     */
    public function GetRespondedAttribute()
    {
        if ((!_auth()->check()) || (@_auth()->user()->role == 'admin')) {

            return false;
        }

        /** @var Authenticatable|User|null $user */
        $user = _auth()->user();

        $job = Job::query()->find($this->slug);
        $responded = $job->chats()
            ->where('candidate_id', '=', @$user->candidate->id)
            ->first();

        if ($responded == null)
            return false;

        return true;
    }

    /**
     * Check if user saved wishlist
     *
     * @return bool
     */
    public function getLikedAttribute(): bool
    {
        return !is_null($this->getLikedByCurrentUser());
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
