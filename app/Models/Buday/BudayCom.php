<?php

namespace App\Models\Buday;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudayCom extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $guarded = [];
}
