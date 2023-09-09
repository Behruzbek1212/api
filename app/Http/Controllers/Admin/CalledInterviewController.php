<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\CalledInterview;
use App\Http\Requests\StoreCalledInterviewRequest;
use App\Http\Requests\UpdateCalledInterviewRequest;
use App\Http\Resources\CalledInterviewResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class CalledInterviewController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $interview  = CalledInterview::with('candidate', 'user')
                    ->where('deleted_at', null)
                    ->orderByDesc('created_at')
                    ->paginate($request->limit ?? 15);

        $data = CalledInterviewResource::collection($interview);
        
        return $this->successPaginationResponse($data);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCalledInterviewRequest $request):JsonResponse
    {
        $request->validated();
        $user = _auth()->user();
        $interview = $user->interview()->create([
           'candidate_id' =>$request->candidate_id,
           'date' => $request->date,
           'status' => 'marked'
        ]);

        return response()->json([
             'status' => true,
             'message' => 'Successfully created'
        ]);
    }



    public function editStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:camed,notCome,marked',
            'interview_id' => 'required|integer'
        ]);
        $interview =  CalledInterview::query()->where('id', $request->interview_id)
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
    public function show(Request $request)
    {
        $interview  = CalledInterview::with('candidate', 'user')
                    ->where('candidate_id', $request->candidate_id)
                    ->where('deleted_at', null)
                    ->firstOrFail();
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
    public function update(UpdateCalledInterviewRequest $request, CalledInterview $calledInterview)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CalledInterview $calledInterview)
    {
        //
    }
}
