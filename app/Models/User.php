<?php

namespace App\Models;

use App\Interfaces\MustVerifyPhone as ContractsMustVerifyPhone;
use App\Traits\MustVerifyPhone;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\JsonResponse;
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
    use HasTransactions;
    use MustVerifyPhone;
    use Notifiable;

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
    ];

    /**
     * Get name mutation
     *
     * @return Attribute
     */
    public function name(): Attribute
    {
        return Attribute::make(
            get: fn () => $this[$this->role]->name
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
        if (! in_array($role, $this->availableRoles))
            return response()->json([
                'status' => false,
                'message' => 'Invalid role'
            ]);

        switch ($role) {
            case 'candidate':
                $this->customer()->update([ 'active' => false ]);
                $this->candidate()->update([ 'active' => true ]);
                break;
            case 'customer':
                $this->candidate()->update([ 'active' => false ]);
                break;
            case 'admin':
                $this->candidate()->update([ 'active' => false ]);
                $this->customer()->update([ 'active' => false ]);
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
     * Display the wishlist
     *
     * @return BelongsToMany
     * @see https://laravel.com/docs/9.x/eloquent-relationships#many-to-many
     */
    public function wishlist(): BelongsToMany
    {
        return $this->belongsToMany(Job::class, 'wishlists');
    }

    /**
     * Display the resumes
     *
     * @return HasMany
     * @see https://laravel.com/docs/9.x/eloquent-relationships#one-to-many
     */
    public function resumes(): HasMany
    {
        return $this->hasMany(Resume::class);
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
}
