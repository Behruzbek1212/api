<?php

namespace App\Models;

use App\Traits\ApiLogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumeBall extends Model
{
    use HasFactory;
    use ApiLogActivity;


    protected $fillable =
    [
        'name',
        'ball',
    ];
}
