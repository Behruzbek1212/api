<?php

namespace App\Models\PortretHr;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortretHr extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'portret_hrs';
    protected $guarded = [];


    protected $casts = [
       'data' => 'array'
    ];
}
