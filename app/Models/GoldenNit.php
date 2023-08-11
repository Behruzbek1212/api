<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoldenNit extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = 
    [
        'name_surname',
        'phone',
        'seniority',
        'telegram_id'
    ];
}
