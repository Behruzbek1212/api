<?php

namespace App\Services;

use App\Models\Chat\Chat;
use App\Models\Customer;
use App\Models\CustomerStatus;
use Illuminate\Support\Collection;

class CustomerStatusAdminService
{
   
    public function create($request)
    {
       $status =  CustomerStatus::query()->create([
            'name' => $request->get('name'),
            'status' => $request->get('status'),
            'customer_id' => $request->get('customer_id')
       ]);
        
       

        return $status;
    }

    public function getCustomerStatus($id)
    {
        $customerStatus = CustomerStatus::where('customer_id', $id)
                    ->where('deleted_at', null)
                    ->get();
                    
        return $customerStatus;

    }



    public function show($request)
    {
        $customerStatus = CustomerStatus::where('id', $request->status_id)
                        ->where('customer_id', $request->customer_id)
                        ->where('deleted_at', null)
                        ->first();
        

        return $customerStatus;
    }


    public function update($request)
    {   
        $customerStatus = CustomerStatus::where('id', $request->status_id)->where('customer_id', $request->customer_id)->first();
        if($customerStatus !== null){
            $chats = Chat::query()->where('customer_id', $request->customer_id)->where('status', $customerStatus->status)->get();
            $customerStatus->update([
                'name' => $request->get('name'),
                'status' => $request->get('status')
            ]);

            if($customerStatus == true){
                foreach($chats as $chat) 
                {
                    $chat->update([
                        'status' =>  $request->get('status')
                    ]);
                }
            }

            return true;
        }
       
        

        return false;
    }


    public function  destroy($request) 
    {
        $customerStatus = CustomerStatus::where('id', $request->status_id)->where('customer_id', $request->customer_id)->first();
        if($customerStatus !== null){
            $chats = Chat::query()->where('customer_id', $request->customer_id)->where('status', $customerStatus->status)->get();
            $customerStatus->delete();

            if($customerStatus == true){
                foreach($chats as $chat) 
                {
                    $chat->update([
                        'status' =>  'review'
                    ]);
                }
            }

            return true;
        }
       
        

        return false;
        
    }
   
  
}
