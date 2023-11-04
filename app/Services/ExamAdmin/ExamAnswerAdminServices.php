<?php

namespace App\Services\ExamAdmin;

use App\Models\Exam\AnswerVariant;
use File;


class ExamAnswerAdminServices
{
 
    public $repository;

    

    public static function getInstance(): ExamAnswerAdminServices
    {
        return new static();
    }


    public function store($request)
    {
    
        if($request->hasFile('image')){
            $path = public_path('exam/answer/image');
            !is_dir($path) &&
                mkdir($path, 0777, true);
    
            $imageName = "jobo_answer_" . time() . '.' . $request->image->extension();
            $request->image->move($path, $imageName);
        }
     
        $queastion  = AnswerVariant::query()->create([
            'questions_for_exam_id' => $request->question_id ?? 0,
            'answer' => $request->answer ?? null,
            'image' => $request->image !== null ? asset('exam/answer/image/' . $imageName) : null,
            'score' => $request->score ?? null
        ]);
        return  $queastion?->id  ?? null;
    }
    
    


    public function update($request, $id)
    {  
        $answer = AnswerVariant::find($id);
        $validated =  $request->validated();
        if($request->hasFile('image')){
            
            $filePath = parse_url($answer->image, PHP_URL_PATH);
            $filePath = ltrim($filePath, '/');
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
            $path = public_path('exam/answer/image');
            !is_dir($path) &&
                mkdir($path, 0777, true);
            
            $imageName = "jobo_answer_" . time() . '.' . $request->image->extension();
            $validated['image'] = asset('exam/answer/image/' . $imageName);
            $request->image->move($path, $imageName);
        }

     
        $answer = $answer->update($validated);

        return  [];
    }

    public function destroy($answer)
    {
        if($answer?->image !== null)
        {
            $filePath = parse_url($answer->image, PHP_URL_PATH);
            $filePath = ltrim($filePath, '/');
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }
   
        $answer->delete();

        return [];
    }
    
}
