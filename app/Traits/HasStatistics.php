<?php

namespace App\Traits;

use App\Models\Candidate;
use App\Models\Customer;
use App\Models\Job;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasStatistics
{
    /**
     * ---
     *
     * @return BelongsToMany
     * @see https://laravel.com/docs/9.x/eloquent-relationships#many-to-many
     */
    public function candidateStats(): BelongsToMany
    {
        return $this->belongsToMany(Candidate::class, 'stats');
    }

    /**
     * ---
     *
     * @return BelongsToMany
     * @see https://laravel.com/docs/9.x/eloquent-relationships#many-to-many
     */
    public function customerStats(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'stats');
    }

    /**
     * ---
     *
     * @return BelongsToMany
     * @see https://laravel.com/docs/9.x/eloquent-relationships#many-to-many
     */
    public function jobStats(): BelongsToMany
    {
        return $this->belongsToMany(Job::class, 'stats');
    }
}
