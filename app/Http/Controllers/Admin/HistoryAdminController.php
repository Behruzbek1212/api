<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Resources\HrDoneWorkedResource;
use App\Services\AllAdminService;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class HistoryAdminController extends Controller
{

   public function allHr(Request $request)
   {  
      $request->validate([
         'start' => 'date',
         'end' => 'date'
      ]);
      $start = $request->start ?? null;
      $end = $request->end ?? null;
      $data = AllAdminService::getAllHr($start, $end, $request->limit);

      return response()->json([
          'data' => $data
      ]);
   }

   public function  getOneHr  (Request $request) 
    { 
      $request->validate([
         'start' => 'date',
         'end' => 'date',
         'hr_id' => 'integer|required',
         'sortType' => 'in:asc,desc'
      ]);
      $user_id = $request->hr_id;
      $start = $request->start ?? null;
      $end = $request->end ?? null;
     
      $sortType = $request->sortType ??  null;
     
      $data = HrDoneWorkedResource::collection(AllAdminService::getOneHr($start, $end, $user_id, $request->limit,   $sortType)); 


       return response()->json([
            'status' => true,
            'data' => $data
       ]);
    }



}
