<?php

namespace App\Models;

use App\Traits\ApiLogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LimitModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    use ApiLogActivity;

    protected $table = 'limits';
    protected $guarded = [];

    public function limit_customer()
    {
        return $this->hasOne(Customer::class, 'limit_id', 'id');
    }
}
