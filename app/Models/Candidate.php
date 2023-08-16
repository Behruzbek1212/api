<?php

namespace App\Models;
use App\Filters\Filterable;
use App\Models\Chat\Chat;
use App\Traits\ApiLogActivity;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Kyslik\ColumnSortable\Sortable;
/**
 * @property User $user
 */
class Candidate extends Model
{
    use Filterable;
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
        'avatar',
        'name',
        'surname',
        'sex',
        'spheres',
        'languages',
        'birthday',
        'address',
        'specialization',
        'education_level',
        'services',
        'test',
        'active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'user_id',


    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'spheres' => 'array',
        'test' => 'array',
        'services' => 'array',
        'languages' => 'array',
        'birthday' => 'datetime',
    ];

    /**
     * Update user data's
     *
     * @param Request $request
     */
    public function updateData(Request $request): void
    {
        $this->update([
            'name' => $request->get('candidate')['name'],
            'surname' => $request->get('candidate')['surname'],
            'specialization' => $request->get('candidate')['specialization'],
            'education_level' => $request->get('candidate')['education_level'],
            'languages' => $request->get('languages'),
            'address' => $request->get('candidate')['address'],
            'birthday' => $request->get('candidate')['birthday'],
        ]);
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
            $this->user()->update(['phone' => $value])
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
            $this->user()->update(['email' => $value])
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

    /**
     * Get chats list
     *
     * @return HasMany
     * @see https://laravel.com/docs/9.x/eloquent-relationships#one-to-many
     */

     public function chats(): HasMany
     {
         return $this->hasMany(Chat::class)
             ->whereHas('resume', function (Builder $table) {
                 $table->whereNull('deleted_at');
             })
             ->whereHas('customer', function (Builder $table) {
                 $table->where('active', '=', true);
                 $table->whereNull('deleted_at');
             })
             ->whereNull('deleted_at');
     }

    /**
     * Get location name
     *
     * @return Attribute
     */
    public function location(): Attribute
    {

        if (empty($this->attributes['address']))
            return Attribute::get(fn () => '');

        $location = $this->belongsTo(Location::class, 'address')->first()['title'] ?? null;

        return Attribute::get(fn () => __($location));
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
