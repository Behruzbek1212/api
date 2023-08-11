<?php

namespace App\Models;

use App\Filters\Filterable;
use App\Traits\ApiLogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class TransactionHistory extends Model
{
    use HasFactory;
    use Filterable;
    // use SoftDeletes;
    //    use LogsActivity;
    //    use ApiLogActivity;

    protected $table = 'transaction_history';
    protected $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function trafic()
    {
        return $this->hasOne(Trafic::class, 'id', 'trafic_id');
    }

    public static function getTableName()
    {
        return self::getTable();
    }
}
