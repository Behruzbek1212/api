<?php

namespace App\Models\Yalpiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class YalpizCom extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $guarded = [];
}
