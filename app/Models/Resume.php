<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $file
 * @property array $data
 * -------------- Relationships --------------
 * @property User $user
 */
class Resume extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'file',
        'data'
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
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'experience'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Display the user profile
     *
     * @return BelongsTo
     * @see https://laravel.com/docs/9.x/eloquent-relationships#one-to-many-inverse
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate experience time
     *
     * @param array $data
     * @return int
     */
    public function calculate_experience(array $data): int
    {
        $employments = $data['employment'];
        $exp_time = 0;

        foreach ($employments as $employment) {
            $start_year = $employment['date']['start']['year'] * 1;
            $start_month = $employment['date']['start']['month'] * 1;

            $end_year = $employment['date']['end']['year'] * 1 ?? 0;
            $end_month = $employment['date']['end']['month'] * 1 ?? 0;

            if (@$employment['date']['present'] === true) {
                $end_year = date('Y');
                $end_month = date('m');
            }

            $exp_time += ($end_year - $start_year) * 12;
            $exp_time += $end_month - $start_month;
        }

        return $exp_time;
    }

    /**
     * Append `experience` column
     *
     * @return Attribute
     */
    public function experience(): Attribute
    {
        return Attribute::get(fn ($_val, $attr) =>
            $this->calculate_experience(json_decode($attr['data'], JSON_PRETTY_PRINT))
        );
    }
}
