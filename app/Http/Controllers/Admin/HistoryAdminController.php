<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class HistoryAdminController extends Controller
{
    public function getHistoryHr(Request $request) 
    {
        $data =  Activity::where(function ($query) {
            $query->where('description', 'admin')
                  ->where('log_name', 'candidates')
                  ->where('event', 'created');
            })
            ->orWhere(function ($query) {
            $query->where('description', 'admin')
                  ->where('log_name', 'comments')
                  ->where('event', 'created');
            })
            ->orderByDesc('created_at')
            ->get();


         return response()->json([
            'status' => true,
            'data' => $data
         ]);
    }
}
