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
            $wishlists = DB::table('wishlists')->where('user_id', $user->id)->get();
           
            if($wishlists !== null){
                foreach($wishlists as $wishlist){
                    DB::table('wishlists')->where('id', $wishlist->id)->delete();
                }
            }

             if($user->role == 'customer') {
                $customer = Customer::where('user_id', $user->id)->first();
               
                if( $customer !== null){
                    DB::table('customers')->where('id', $customer->id)->delete();
                    $jobs =   DB::table('jobs')->where('customer_id', $customer->id)->get();
                    $chats = DB::table('chats')->where('customer_id', $customer->id)->get();
                    if($jobs !== null){
                        foreach($jobs as $job){
                            DB::table('jobs')->where('id', $job->id )->delete();
                        }
                    }
                    if($chats !== null){
                        foreach($chats as $chat){
                            DB::table('chats')->where('id', $chat->id)->delete();
                        }
                    }
                }  
               
                return response()->json([
                    'status' => true,
                    'message' => 'success deleted',
                    'user'=>$user,
                    'customer' => $customer,
              ]);

             } elseif($user->role == 'candidate'){
                $candidate= DB::table('candidates')->where('user_id', $user->id)->first();
                $chats = DB::table('chats')->where('candidate_id', $candidate->id)->get();
                $resumes = Resume::where('user_id',  $user->id)->get();
                DB::table('candidates')->where('user_id', $user->id)->delete();
              
                if ($resumes !== null){
                    foreach($resumes as $resume){
                        DB::table('resumes')->where('id', $resume->id)->delete();
                        DB::table('chats')->where('resume_id', $resume->id)->delete();
                    }
                }
                if($chats !== null){
                    foreach($chats as $chat){
                        DB::table('chats')->where('id', $chat->id)->delete();
                    }
                }
                return response()->json([
                'status' => true,
                'message' => 'success deleted',
                'user'=>$user,
                'chat' => $chats,
                'candidate' => $candidate,
               ]);
             }
            
            
         }    

         return response()->json([
            'status' => false,
            'message' => 'user not found',
         ]);
          
        
    }
}
