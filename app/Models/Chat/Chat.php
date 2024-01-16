<?php

namespace App\Models\Chat;

use App\Models\Candidate;
use App\Models\Customer;
use App\Models\CustomerChatComment;
use App\Models\Job;
use App\Models\Resume;
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
        'resume',
        'job',
        'chatComment'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'customer_exists',
        'candidate_exists',
        'resume_exists',
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
    public function getCandidateAttribute(): BelongsTo|Model|Candidate|null
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
    public function getCustomerAttribute(): BelongsTo|Model|Customer|null
    {
        return $this->customer()->first();
    }

    /**
     * Get messages
     *
     * @return BelongsTo
     * @see https://laravel.com/docs/9.x/eloquent-relationships#one-to-one-defining-the-inverse-of-the-relationship
     */
    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class);
    }

    /**
     * Get resume attribute
     *
     * @return Resume|Model|BelongsTo
     */
    public function getResumeAttribute(): BelongsTo|Model|Resume|null
    {
        return $this->resume()->first();
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
    public function getJobAttribute(): BelongsTo|Model|Job|null
    {
        return $this->job()->first();
    }


    public function chatComment(): HasMany
    {
        return $this->hasMany(CustomerChatComment::class);
    }

    public function getChatCommentAttribute()
    {
       return $this->chatComment()->whereNull('deleted_at')
                ->orderBy('created_at', 'desc')->get();
    }
    
}
