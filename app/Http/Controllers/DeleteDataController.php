<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Customer;
use App\Models\Job;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeleteDataController extends Controller
{
    public function delete(Request $request)
    {
         $user = User::where('phone', $request->phone)->first();
        
    
        if($user !== null){
            DB::table('users')->where('id', $user->id)->delete();
             if($user->role == 'customer') {
                $customer = Customer::where('user_id', $user->id)->first();
                if( $customer !== null){
                    DB::table('customers')->where('id', $customer->id)->delete();
                    $jobs =   DB::table('jobs')->where('customer_id', $customer->id)->get();
                    if($jobs !== null){
                        foreach($jobs as $job){
                            DB::table('jobs')->where('id', $job->id )->delete();
                        }
                    }
                }  

                return response()->json([
                    'status' => true,
                    'user'=>$user,
                    'customer' => $customer,
              ]);

             } elseif($user->role == 'candidate'){

                $condidat =  DB::table('candidates')->where('user_id', $user->id)->delete();
                $resumes = Resume::where('user_id',  $user->id)->get();
              
                if ($resumes !== null){
                    foreach($resumes as $resume){
                        DB::table('resumes')->where('id', $resume->id)->delete();
                    }
                }
                return response()->json([
                'status' => true,
                'user'=>$user,
                'condidat' => $condidat,
                'resume' => $resumes
               ]);
             }
            
            
         }    

         return response()->json([
            'status' => false,
            'message' => 'user not found',
         ]);
          
        
    }
}
