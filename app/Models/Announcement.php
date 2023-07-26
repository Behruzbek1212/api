<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'announcements';

    protected $fillable = 
    [
       'post',
       'status',
       'time'
    ];

    protected $casts = [
        'post' => 'array',
    ];
}
