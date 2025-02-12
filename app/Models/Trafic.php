<?php

namespace App\Models;

use App\Traits\ApiLogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Trafic extends Model
{
    use HasFactory;
    // use SoftDeletes;
    //    use LogsActivity;
    use ApiLogActivity;
    const NOT_DROP_TYPE = [4];
    const KEY_FOR_SITE = 'site';
    const KEY_FOR_TELEGRAM = 'telegram';

    protected $table = 'trafics';
    protected $guarded = [];

    public function trafic_price()
    {
        return $this->hasOne(TraficPrice::class, 'id', 'trafic_price_id');
    } 
}
