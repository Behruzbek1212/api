<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerStatus extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = 
    [   
        'customer_id',
        'name',
        'required',
        'status'
    ];


    protected $casts = [
       'name' => 'array'
    ];
    
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
