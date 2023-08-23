<?php

namespace App\Services;


use Illuminate\Support\Collection;

class CustomerChatCommentService
{
    protected $user;

    public function __construct()
    {
      $this->user = _auth()->user();
    }
    
    public function create($request)
    {

        $customer = $this->user->customer->chatComment()->create([
           'chat_id' => $request->chat_id,
           'comment' => $request->comment
        ]);

        return  $customer;
    }

    public function show($id) 
    {
        
        $comment = $this->user->customer->chatComment()->find($id);

        return $comment;
    }


    public function update($request)
    {
    
        $comment = $this->user->customer->chatComment()->findOrFail($request->comment_id)
                        ->update([
                            'comment' => $request->comment
                         ]);

        return $comment;
    }


    public function  destroy($request) 
    {

        $comment = $this->user->customer->chatComment()->findOrFail($request->comment_id);

        $comment->delete();

        return $comment;
    }
   
  
}
