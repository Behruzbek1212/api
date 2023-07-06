<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Candidate;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request):JsonResponse
    {
    	$request->validate([
            'body'=>'required',
            'candidate_id'=> 'integer|required'
        ]);

        $user = _auth()->user()->id;
        if(_auth()->user()->role == 'admin'){
            $comment  = Comment::query()->create([
                'user_id' =>  $user,
                'candidate_id'=> $request->candidate_id,
                'body' => $request->body
           ]);
   
   
           return response()->json([
               'status' => true,
               'message' => 'Successfully comment created'
           ]);
        }

        return response()->json([
            'status' => true,
            'data' => [],
            'message' => 'Role invalid'
        ]);
       
      
    }


    public function getComment(Request $request):JsonResponse 
    {
         
        $request->validate([
            'candidate_id'=> 'integer|required'
        ]);
      
        $candidate = Candidate::query()
        ->with(['comments', 'comments.user'])
        ->where('active', '=', true)
        ->where('id', '=', $request->candidate_id)
        ->firstOrFail();
        
        $comment =  CommentResource::collection($candidate->comments);
        return response()->json([
            'status' => true,
            'data' => $comment
        ]);
    }
}
