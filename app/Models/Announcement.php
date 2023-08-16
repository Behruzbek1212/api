<?php

namespace App\Models;

use App\Traits\ApiLogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory;
    use SoftDeletes;
    use ApiLogActivity;

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
