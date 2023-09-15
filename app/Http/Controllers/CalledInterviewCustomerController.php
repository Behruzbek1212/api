<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCalledInterviewRequest;
use App\Http\Requests\UpdateCalledInterviewRequest;
use App\Http\Resources\CalledInterviewResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalledInterviewCustomerController extends Controller
{
    use ApiResponse;
    protected $user;
    
    public function __construct()
    {
        $this->user = _auth()->user();
    }
    

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $interview  =  $this->user->interview()->with('candidate', 'user.customer')
                    ->where('deleted_at', null)
                    ->orderByDesc('created_at')
                    ->paginate($request->limit ?? 15);

        
        
        return $this->successPaginationResponse($interview);
    }


    public function store(StoreCalledInterviewRequest $request):JsonResponse
    {
        $request->validated();
        
        $interview = $this->user->interview()->create([
           'candidate_id' =>$request->candidate_id,
           'date' => $request->date,
           'status' => 'marked',
           'chat_id'=> $request->chat_id ?? null
        ]);

        return response()->json([
             'status' => true,
             'message' => 'Successfully created'
        ]);
    }



    public function editStatus(Request $request):JsonResponse
    {
        $request->validate([
            'status' => 'required|in:camed,notCome,marked',
            'interview_id' => 'required|integer'
        ]);
        $interview =   $this->user->interview()->where('id', $request->interview_id)
                      ->update([
                         'status' => $request->status
                      ]);

        return response()->json([
            'status' => true,
            'message' => 'Successfully updated status'
        ]);              
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request):JsonResponse
    {   
        
        $interview  = $this->user->interview()->with('candidate', 'user.customer')
                    ->where('chat_id', $request->chat_id)
                    ->where('deleted_at', null)
                    ->orderByDesc('created_at')
                    ->get();
        if($interview !== null){
            return response()->json([
                'status' => true,
                'data' => $interview
             ]);  
        }
        return response()->json([
            'status' => false,
            'data' => []
        ]);         
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCalledInterviewRequest $request):JsonResponse
    {
      
        $interview =  $this->user->interview()->where('id', $request->interview_id)
                      ->update([
                         'date' => $request->date
                      ]);

        return response()->json([
            'status' => true,
            'message' => 'Successfully updated date'
        ]);  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request):JsonResponse
    {
        $interview =  $this->user->interview()->where('id', $request->interview_id)
                      ->delete();

        return response()->json([
            'status' => true,
            'message' => 'Successfully delete'
        ]);  
    }
}
