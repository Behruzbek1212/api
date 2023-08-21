<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCustomerChatCommentRequest;
use App\Http\Requests\UpdateCustomerChatCommentRequest;
use App\Services\CustomerChatCommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerChatCommentController extends Controller
{
    protected  $customerChatCommentService;

    public function __construct(CustomerChatCommentService $customerChatCommentService)
    {
        $this->customerChatCommentService =  $customerChatCommentService;
    }
    
    public function getComment($id):JsonResponse 
    {   
        
        $comment = _auth()->user()->customer->chatComment()->where('chat_id', $id)->orderByDesc('created_at')->get();

        return response()->json([
             'status' => true,
             'comment' => $comment
         ]);
    }

    public function create(CreateCustomerChatCommentRequest $request):JsonResponse
    {
        $request->validated();
           
        $this->customerChatCommentService->create($request);

        return response()->json([
            'status' => true,
            'message' => 'Successfully created'
        ]);
    }


    public function show($id): JsonResponse
    {
        $comment = $this->customerChatCommentService->show($id);
         
        return response()->json([
            'status' => true,
            'comment' => $comment
        ]);
    }


    public function update(UpdateCustomerChatCommentRequest $request):JsonResponse
    {   
        $request->validated();

        $this->customerChatCommentService->update($request);

        return response()->json([
            'status' => true,
            'message' => 'Successfully updated'
        ]);
    }

    public function destroy(Request $request) 
    {
        $request->validate([
           'comment_id' => 'required|integer'
        ]);

        $comment = $this->customerChatCommentService->destroy($request);
        
        return response()->json([
            'status' => true,
            'message' => 'Successfully deleted'
        ]);
    }
}
