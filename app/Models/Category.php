<?php

namespace App\Models;

use App\Traits\ApiLogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;
    // use ApiLogActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'text'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'count'
    ];
    protected $casts = [
        'text' => 'array',
    ];
    public $timestamps = false;

    /**
     * Get jobs list
     *
     * @return HasMany
     */
    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'category_id');
    }

    /**
     * Get count
     *
     * @return int
     */
    public function getCountAttribute(): int
    {
        return $this->jobs()->count();
    }
}
