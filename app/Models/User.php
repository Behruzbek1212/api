<?php

namespace App\Models;

use App\Interfaces\MustVerifyPhone as ContractsMustVerifyPhone;
use App\Traits\ApiLogActivity;
use App\Traits\HasStatistics;
use App\Traits\MustVerifyPhone;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\Contracts\HasApiTokens as ContractsHasApiTokens;
use Laravel\Sanctum\HasApiTokens;
use PayzeIO\LaravelPayze\Traits\HasTransactions;

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
 * @property Resume[] $resumes
 * @property Customer $customer
 * @property Candidate $candidate
 */
class User extends Authenticatable implements MustVerifyEmail, ContractsMustVerifyPhone, ContractsHasApiTokens
{
    use HasApiTokens;
    use HasFactory;
    use HasStatistics;
    use HasTransactions;
    use MustVerifyPhone;
    use Notifiable;
    use SoftDeletes;
    use ApiLogActivity;
    /**
     * Available roles list
     *
     * @var array<int, string>
     */


    protected $availableRoles = [
        'candidate',
        'customer',
        'admin'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'phone',
    //     'role',
    //     'email',
    //     'password',
    // ];

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pivot',
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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'subrole' => 'array',
    ];

    /**
     * Get name mutation
     *
     * @return Attribute
     */
    public function name(): Attribute
    {
        //        dd($this->role);
        return Attribute::make(
            get: fn () => $this[$this->role]->name ?? null
        );
    }

    /**
     * Switch user role
     *
     * @param string $role
     * @return JsonResponse
     */
    public function changeRole(string $role): JsonResponse
    {
        if (!in_array($role, $this->availableRoles))
            return response()->json([
                'status' => false,
                'message' => 'Invalid role'
            ]);

        switch ($role) {
            case 'candidate':
                $this->customer()->update(['active' => false]);
                $this->candidate()->update(['active' => true]);
                break;
            case 'customer':
                $this->customer()->update(['active' => true]);
                $this->candidate()->update(['active' => false]);
                break;
            case 'admin':
                $this->candidate()->update(['active' => false]);
                $this->customer()->update(['active' => false]);
                break;
            default:
                return response()->json([
                    'status' => false,
                    'message' => 'Error occurred'
                ]);
        }

        $this->role = $role;
        $this->save();

        return response()->json([
            'status' => true,
            'message' => 'Role updated successfully'
        ]);
    }

    /**
     * Change user profile avatar
     *
     * @param string $url
     * @return JsonResponse
     */
    public function changeAvatar(string $url): JsonResponse
    {
        $role = $this->role;

        match ($role) {
            'customer' => $this->customer()->update(['avatar' => $url]),
            'candidate' => $this->candidate()->update(['avatar' => $url])
        };

        return response()->json([
            'status' => true,
            'message' => 'Profile avatar updated successfully'
        ]);
    }

    /**
     * Update user data's
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateData(Request $request): JsonResponse
    {
        $role = $this->role;

        if (!is_null($request->input('email')) && $request->input('email') != $this->email) {
            $this->update([
                'email' => $request->input('email'),
                'email_verified_at' => null
            ]);
        }

        match ($role) {
            'customer' => $this->customer->updateData($request),
            'candidate' => $this->candidate->updateData($request)
        };

        return response()->json([
            'status' => true,
            'message' => 'User data updated successfully'
        ]);
    }


    /**
     * Update user data's
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateCandidateServices(Request $request): JsonResponse
    {
        //     resume: Boolean,
        // conversation: Booelan
        $data = $request->json()->all();

        $this->candidate()->update(['services' => $data]);

        return response()->json([
            'status' => true,
            'message' => 'User data updated successfully'
        ]);
    }

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
     * Check if user role is customer.
     *
     * @return bool
     */
    public function isCustomer(): bool
    {
        return !is_null($this->customer);
    }

    /**
     * Check if user role is customer.
     *
     * @return bool
     */
    public function isCandidate(): bool
    {
        return !is_null($this->candidate);
    }

    /**
     * Display the customer information
     *
     * @return HasOne
     * @see https://laravel.com/docs/9.x/eloquent-relationships#one-to-one
     */
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    /**
     * Display the candidate information
     *
     * @return HasOne
     * @see https://laravel.com/docs/9.x/eloquent-relationships#one-to-one
     */
    public function candidate(): HasOne
    {
        return $this->hasOne(Candidate::class);
    }

    /**
     * Get the entity's notifications.
     *
     * @return MorphMany
     * @see https://laravel.com/docs/9.x/eloquent-relationships#one-to-many-polymorphic-relations
     * @see https://laravel.com/docs/9.x/notifications
     */
    public function notifications(): MorphMany
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')->latest();
    }

    /**
     * Display the jobs wishlist
     *
     * @return BelongsToMany
     * @see https://laravel.com/docs/9.x/eloquent-relationships#many-to-many
     */
    public function jobsWishlist(): BelongsToMany
    {
        return $this->belongsToMany(Job::class, 'wishlists');
    }

    /**
     * Display the candidates wishlist
     *
     * @return BelongsToMany
     * @see https://laravel.com/docs/9.x/eloquent-relationships#many-to-many
     */
    public function candidateWishlist(): BelongsToMany
    {
        return $this->belongsToMany(Candidate::class, 'wishlists');
    }

    /**
     * Display the resumes
     *
     * @return HasMany
     * @see https://laravel.com/docs/9.x/eloquent-relationships#one-to-many
     */
    public function resumes(): HasMany
    {
        return $this->hasMany(Resume::class, 'user_id', 'id')->orderByDesc('id');
    }

    public function transaction_histories(): HasMany
    {
        return $this->hasMany(TransactionHistory::class, 'user_id', 'id');
    }

    /**
     * Display resume information
     *
     * @param Resume $resume
     * @return Resume|null
     * @see https://laravel.com/docs/9.x/eloquent-relationships#one-to-many
     */
    public function resume(Resume $resume): ?Resume
    {
        foreach ($this->resumes as $_resume) {
            if ($_resume->id === $resume->id) {
                return $_resume;
            }
        }

        return null;
    }


    public function comment(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function interview(): HasMany
    {
        return $this->hasMany(CalledInterview::class);
    }

    public function receivesBroadcastNotificationsOn() { return 'users.'.$this->id; }
}
