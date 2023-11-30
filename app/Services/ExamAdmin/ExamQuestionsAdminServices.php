<?php

namespace App\Services\ExamAdmin;

use App\Models\Exam\ExamQuestion;
use App\Models\Exam\Question;
use File;


class ExamQuestionsAdminServices
{
 
    public $repository;

   
    public static function getInstance(): ExamQuestionsAdminServices
    {
        return new static();
    }


    public function store($request)
    {
      
        if($request->hasFile('image')){
            $path = public_path('exam/question/image');
            !is_dir($path) &&
                mkdir($path, 0777, true);
    
            $imageName = "jobo" . time() . '.' . $request->image->extension();
            $request->image->move($path, $imageName);
        }
        if($request->hasFile('video')){
            $path = public_path('exam/question/video');
            !is_dir($path) &&
                mkdir($path, 0777, true);
    
            $videoName = "jobo_video" . time() . '.' . $request->video->extension();
            $request->video->move($path,   $videoName);
        }
     
        $question  = Question::query()->create([
            'question' => $request->question,
            'video' => $request->video !== null ? asset('exam/question/video/'.   $videoName): null,
            'image' => $request->image !== null ? asset('exam/question/image/' . $imageName) : null,
            'position' => $request->position ?? null
        ]);
        $decrement = ExamAdminServices::getInstance()->question_count('plus', $request->exam_id);
        $examQuestion = ExamQuestion::query()->create([
            'exam_id' => $request->exam_id,
            'questions_for_exam_id' => $question->id
        ]);
        return  $question?->id  ?? null;
    }
    
    public function showWithQuestion($exam)
    {
        $queastion = $exam->examQuestion()->with('question')->get();

        return $queastion;
    }


    public function update($request, $question)
    {  
        
        $validated =  $request->validated();
        if($request->hasFile('image')){
            
            $filePath = parse_url($question->image, PHP_URL_PATH);
            $filePath = ltrim($filePath, '/');
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
            $path = public_path('exam/question/image');
            !is_dir($path) &&
                mkdir($path, 0777, true);
            
            $imageName = "jobo" . time() . '.' . $request->image->extension();
            $validated['image'] = asset('exam/question/image/' . $imageName);
            $request->image->move($path, $imageName);
        }

        if($request?->hasFile('video')){
            $filePath = parse_url($question->video, PHP_URL_PATH);
            $filePath = ltrim($filePath, '/');
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
            $path = public_path('exam/question/video');
            !is_dir($path) &&
                mkdir($path, 0777, true);
            
            $videoName = "jobo_video" . time() . '.' . $request->video->extension();
            $validated['video'] = asset('exam/question/video/' . $videoName);
            $request->video->move($path, $videoName);
        }
     
        $exam = $question->update($validated);

        return  [];
    }

    public function destroy($question)
    {
        if($question?->video !== null)
        {
            $filePath = parse_url($question->video, PHP_URL_PATH);
            $filePath = ltrim($filePath, '/');
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }
        if($question?->image !== null)
        {
            $filePath = parse_url($question->image, PHP_URL_PATH);
            $filePath = ltrim($filePath, '/');
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }
        $question->answerVariants()->delete();
        $examQuestion = ExamQuestion::where('questions_for_exam_id', $question->id)->first();
        if($examQuestion !== null){
            $decrement = ExamAdminServices::getInstance()->question_count('minus', $examQuestion->exam_id);
            $examQuestion->delete();
        }
       

        $question->delete();

        return [];
    }
    
}
