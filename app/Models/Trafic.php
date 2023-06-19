<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trafic extends Model
{
    use HasFactory;
    use SoftDeletes;
    const NOT_DROP_TYPE = [4];

    protected $table = 'trafics';
    protected $guarded = [];

    public function jobs()
    {
        return $this->hasMany(Job::class, 'trafic_id', 'id');
    }
}
