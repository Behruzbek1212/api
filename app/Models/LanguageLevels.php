<?php

namespace App\Models;

use App\Traits\ApiLogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageLevels extends Model
{
    use HasFactory;
    use ApiLogActivity;



     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $fillable = [
        'name',
        'text'
    ];


    protected $casts = [
        'text' => 'array',
    ];

}
