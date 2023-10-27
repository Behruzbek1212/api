<?php

namespace App\Http\Controllers;

use App\Models\TestResult;
use App\Http\Requests\StoreTestResultRequest;
use App\Http\Requests\UpdateTestResultRequest;
use App\Http\Resources\ReatingTestCandidateResource;
use App\Http\Resources\TestResultResource;
use Illuminate\Http\Request;
use App\Services\TestResultService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class TestResultController extends Controller
{
    protected $testResultService;
    use ApiResponse;

    public function __construct(TestResultService $testResultService)
    {
        $this->testResultService = $testResultService;
    }

    public function allTestResultCandidate()
    {
        $data = ReatingTestCandidateResource::collection($this->testResultService->allTestCount());
        return  response()->json(['status' => true,   'result' => $data]);
    }

    /**
     * Display a listing of the resource.
     */
    public function getAll(Request $request):JsonResponse
    {
        try
        {
            $data = TestResultResource::collection($this->testResultService->getAll($request));
            return  response()->json(['status' => true,   'result' => $data]);
        }
        catch(Exception $e)
        {
            return response()->json([
                'status' =>  false,
                'message'=> $e->getMessage(),
                'result' => []
            ]);
        }
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTestResultRequest $request):JsonResponse
    {
        $request->validated();
        try 
        {
            $result = $this->testResultService->store($request);
            if($result !== []) {
                return response()->json([
                    'status' => true,
                    'message' => 'Successfully created test result',
                    'data' => $result,
                ]);
            } 
            return response()->json([
                'status' => false,
                'message' => 'You have passed this test before',
                'data' => [],
            ]);
        }
        catch (Exception $e) 
        {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ]);
        }
        
    }


    public function getCandidateTestResult(Request  $request):JsonResponse
    {
        $request->validate([
            'customer_id' => 'integer|nullable'
        ]);

        try
        {
            $result  = $this->testResultService->getCandidateTest($request);
            return response()->json([
                'status' => true,
                'message' => 'Get candidate test',
                'data' => $result,
            ]);
        }
        catch (Exception $e) 
        {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ]);
        }
      

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request):JsonResponse
    {
        $request->validate([
           'test_id' => 'required|integer'
        ]);

        try 
        {
            $result  = $this->testResultService->show($request);
          
            return response()->json([
                'status' => true,
                'message' => 'Get one candidate test',
                'data' => $result,
            ]); 
        }
        catch (Exception $e) 
        {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ]);
        }
    }


    public function downloadTestResult(string|int $id)
    {
        $data = TestResult::with('candidate')
                            ->whereHas('candidate', function ($query) {
                                $query->where('deleted_at', null);
                            })->where('candidate_id', $id)->first();

        $candidate = $data->candidate;
      
        return $this->testResultService
                ->loadOne(compact('data', 'candidate'))
                ->download($candidate->name . '.pdf');
                // ->loadOne(compact('data', 'candidate', 'resume_id', 'experience'))
                // ->download($candidate->name . '.pdf');
    }

    public function downloadTestCustomer($id)
    {
        
        $data = TestResult::with('candidate', 'customer')
                            ->whereHas('candidate', function ($query) {
                              $query->where('deleted_at', null);
                            })
                           ->where('customer_id', $id)
                           ->where('deleted_at', null)
                           ->get();

        $customer = $data[0]['customer']['name'];
      
        if($data !== []){
            return $this->testResultService
                ->loadCustomer(compact('data'))
                ->download($customer . '.pdf');
        } 
         
        return response()->json([
            'status' => false,
            'message' => 'Not Fount'
        ]);

    }
}
