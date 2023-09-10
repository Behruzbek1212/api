<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PartyHr extends Model
{
    use HasFactory;


    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'telegram_id';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'telegram_id',
        'identification',
        'info'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'info' => 'json',
    ];


      /**
     * Get url parameter for identification
     *
     * @return HasOne
     */
    public function link(): HasOne
    {
        return $this->hasOne(PartyCrater::class, 'identification', 'identification');
    }
}
