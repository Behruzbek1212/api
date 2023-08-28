<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{
    public function  backup() 
    {
        Artisan::call('db:backup');
        return true;
    }
}
