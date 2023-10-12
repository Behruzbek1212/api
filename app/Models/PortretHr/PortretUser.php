<?php

namespace App\Models\PortretHr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortretUser extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'portret_users';
    protected $guarded = [];
     
}
