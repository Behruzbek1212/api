<?php

namespace App\Models;

use App\Traits\ApiLogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    use ApiLogActivity;
    protected $table = 'questions';
    protected $guarded = [];

    // public function trafic_price()
    // {
    //     return $this->hasOne(TraficPrice::class, 'id', 'trafic_price_id');
    // }
}
