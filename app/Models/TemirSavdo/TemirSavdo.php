<?php

namespace App\Models\TemirSavdo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemirSavdo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
}
