<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LimitModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'limits';
    protected $guarded = [];

    public function limit_customer()
    {
        return $this->hasOne(Customer::class, 'limit_id', 'id');
    }
}
