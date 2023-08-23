<?php

namespace App\Models;

use App\Traits\ApiLogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class TraficPrice extends Model
{
    use HasFactory;
    // use SoftDeletes;
    //    use LogsActivity;
    use ApiLogActivity;

    protected $table = 'trafic_prices';
    protected $guarded = [];

    // public function jobs()
    // {
    //     return $this->hasMany(Job::class, 'trafic_id', 'id');
    // }
}
