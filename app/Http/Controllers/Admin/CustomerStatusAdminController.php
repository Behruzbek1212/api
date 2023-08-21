<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CustomerStatusAdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerStatusAdminController extends Controller
{
    protected $customerStatusService;

    public function __construct(CustomerStatusAdminService $customerStatusService)
    {
        $this->customerStatusService = $customerStatusService;
    }

    

    
    public function create(Request $request):JsonResponse
    {
        $request->validate([
            'name' => 'required|array',
            'status'=> 'required|string',
            'customer_id' => 'required|integer'
        ]);

        $status =  $this->customerStatusService->create($request);
        
        return response()->json([
            'status' => true,
            'message' => "Successfully created "
        ]);
    }
    
    public function getCustomerStatus($id):JsonResponse
    {
        $status = $this->customerStatusService->getCustomerStatus($id);

        return response()->json([
            'status' => true,
            'data' => $status
        ]);
    }

    public function show(Request $request)
    {
        $request->validate([
            'status_id' => 'required|integer',
            'customer_id' => 'required|integer'
        ]);

        $status = $this->customerStatusService->show($request);

        return response()->json([
            'status' => true,
            'data' => $status
        ]);
    }


    public function update(Request $request):JsonResponse
    {
        $request->validate([
            'status_id' => 'required|integer',
            'name' => 'required|array',
            'status'=> 'required|string',
            'customer_id' => 'required|integer'
        ]);

        $this->customerStatusService->update($request);
       

        return response()->json([
            'status' => true,
            'message' => "Successfully updated "
        ]);
    }
    


    public function destroy(Request $request) 
    {
        $request->validate([
            'status_id' => 'required|integer',
            'customer_id' => 'required|integer'
        ]);
        
        $this->customerStatusService->destroy($request);

        return response()->json([
            'status' => true,
            'message' => "Successfully deleted "
        ]);

    }
}
