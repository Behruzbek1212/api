<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use JsonException;

class ResumeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $params = $request->validate([
            'limit' => ['integer', 'nullable']
        ]);
        $resumes = Resume::query()->paginate($params['limit'] ?? 7);


        return response()->json([
            'status' => true,
            'data' => $resumes,
         ]);
    }
    /**
     * Get resume information
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        
        $resume = Resume::findOrFail($id);
        return response()->json([
            'status' => true,
            'data' => $resume['data']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
         $request->validate([
              'user_id' => ['required'],
              'data' => ['required']
         ]);
        

        $resumes = Resume::query()->create([
            'user_id' => $request->user_id,
            'data' => $request->data
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Resume successfully created'
        ]);
    }

    /**
     * Update data
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {

      
        $request->validate([
            'resume_id' => ['integer', 'required'],
            'data' => ['required']
        ]); 
        
        $resume =  Resume::findOrFail($request->resume_id)
            ->update([
                'data' => $request->input('data')
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Resume successfully updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string|int $id
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    { 
        $request->validate([
            'resume_id' => ['integer', 'required']
        ]);
        $resume = DB::table('resumes')->where('id', '=', $request->resume_id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Resume successfully deleted'
        ]);
    }

   
}
