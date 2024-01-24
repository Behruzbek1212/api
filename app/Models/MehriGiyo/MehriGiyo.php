<?php

namespace App\Models\MehriGiyo;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MehriGiyo extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $guarded = [];
}
