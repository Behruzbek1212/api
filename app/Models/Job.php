<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string title
 * @property string $salary
 * @property string $type
 * @property array $requirements
 * @property array|null $tasks
 * @property array|null $advantages
 * @property integer $location_id
 */
class Job extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'title',
        'salary',
        'type',
        'requirements',
        'tasks',
        'advantages',
        'location_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'requirements' => 'array',
        'tasks' => 'array',
        'advantages' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
