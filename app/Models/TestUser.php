<?php

namespace App\Models;

//use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class TestUser extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'name',
        'surname',
        'sex',
        'position',
        'phone',
        'company_id',
        'test',
        'password'
    ];

    protected $casts = [
        'test'=>'array'
    ];
    protected $hidden = [
        'password',
    ];
}
