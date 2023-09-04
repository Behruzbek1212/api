<?php

namespace App\Models;

use App\Models\Chat\Chat;
use App\Traits\ApiLogActivity;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
    use ApiLogActivity;

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
        'services',
        'active',
        'limit_id',
        'limit_start_day',
        'limit_end_day',
        'telegram_id'
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
        'services' => 'array',
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
            'telegram_id' => $request->get('customer')['telegram_id'] ?? null
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
        return $this->hasMany(Job::class, 'customer_id', 'id');
    }

    /**
     * Display the resumes
     *
     * @return HasMany
     * @see https://laravel.com/docs/9.x/eloquent-relationships#one-to-many
     */
    public function testUsers(): HasMany
    {
        return $this->hasMany(TestUser::class, 'company_id', 'id');
    }


    /**
     * Get chats list
     *
     * @return HasMany
     * @see https://laravel.com/docs/9.x/eloquent-relationships#one-to-many
     */
    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class)
            ->withWhereHas('resume', function (Builder $table) {
                $table->whereNull('deleted_at');
            })
            ->withWhereHas('candidate', function (Builder $table) {
                $table->where('active', '=', true);
                $table->whereNull('deleted_at');
            })
            ->whereNull('deleted_at');
    }

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


    public function limit_customer()
    {
        return $this->hasOne(LimitModel::class, 'id', 'limit_id');
    }

    /**
     * Display the customer status
     *
     * @return HasMany
     * @see https://laravel.com/docs/10.x/eloquent-relationships#one-to-many
     */

    public function customerStatus():HasMany
    {
        return $this->hasMany(CustomerStatus::class);
    }
    
    /**
     * Display the chat comment
     *
     * @return HasMany
     * @see https://laravel.com/docs/10.x/eloquent-relationships#one-to-many
     */

    public function chatComment(): HasMany
    {
        return $this->hasMany(CustomerChatComment::class);
    }
 
    /**
     * Display the announcement
     *
     * @return HasMany
     * @see https://laravel.com/docs/10.x/eloquent-relationships#one-to-many
     */

    public function announcement():HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    /**
     * Display the test result
     *
     * @return HasMany
     * @see https://laravel.com/docs/10.x/eloquent-relationships#one-to-many
     */
    
    public function testResult():HasMany
    {
       return $this->hasMany(TestResult::class);
    }
}
