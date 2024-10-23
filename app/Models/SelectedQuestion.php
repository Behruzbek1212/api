<?php

namespace App\Models;

use App\Traits\ApiLogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectedQuestion extends Model
{
    use HasFactory;
    // use ApiLogActivity;
    protected $table = 'selected_questions';
    protected $guarded = [];

    protected $casts = [
        'questions' => 'array',
        'salary' => 'array',
    ];

    // public function trafic_price()
    // {
    //     return $this->hasOne(TraficPrice::class, 'id', 'trafic_price_id');
    // }
}
