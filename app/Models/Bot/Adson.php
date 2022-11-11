<?php

namespace App\Models\Bot;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Adson extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bot_adson';

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
        return $this->hasOne(AdsonCrater::class, 'identification', 'identification');
    }
}
