<?php

namespace App\Models\Chat;

use App\Models\Candidate;
use App\Models\Customer;
use App\Models\Job;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'job_slug',
        'resume_id',
        'customer_id',
        'candidate_id',
        'status'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'customer',
        'candidate',
        'job'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at'
    ];

    /**
     * Get messages
     *
     * @return HasMany
     * @see https://laravel.com/docs/9.x/eloquent-relationships#one-to-many
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Messages::class);
    }

    /**
     * Get messages
     *
     * @return BelongsTo
     * @see https://laravel.com/docs/9.x/eloquent-relationships#one-to-one-defining-the-inverse-of-the-relationship
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Get candidate attribute
     *
     * @return Candidate|Model|BelongsTo
     */
    public function getCandidateAttribute(): BelongsTo|Model|Candidate
    {
        return $this->candidate()->first();
    }

    /**
     * Get messages
     *
     * @return BelongsTo
     * @see https://laravel.com/docs/9.x/eloquent-relationships#one-to-one-defining-the-inverse-of-the-relationship
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get customer attribute
     *
     * @return Customer|Model|BelongsTo
     */
    public function getCustomerAttribute(): BelongsTo|Model|Customer
    {
        return $this->customer()->first();
    }

    /**
     * Get messages
     *
     * @return BelongsTo
     * @see https://laravel.com/docs/9.x/eloquent-relationships#one-to-one-defining-the-inverse-of-the-relationship
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Get candidate attribute
     *
     * @return Job|Model|BelongsTo
     */
    public function getJobAttribute(): BelongsTo|Model|Job
    {
        return $this->job()->first();
    }
}
