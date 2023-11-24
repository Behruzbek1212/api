<?php

namespace App\Services\ExamAdmin;

use App\Http\Resources\ExamAdmin\ExamAdminResource;
use App\Http\Resources\ExamAdmin\ExamQuestionResource;
use App\Models\Exam;
use App\Models\Exam\Question;
use App\Repository\Exam\ExamRepository;
use File;

class ExamAdminServices
{
 
    public $repository;

    public function __construct(ExamRepository $data)
    {
        $this->repository = $data;
    }

    public static function getInstance(): ExamAdminServices
    {
        return new static(ExamRepository::getInctance());
    }

    public function list()
    {
        return ExamAdminResource::collection($this->repository->list(function ($builder) {
            return $builder
                ->when(request('title') , function ($query){
                    $query->where('key', request('title'))
                          ->orWhere('name', 'like', '%' .   request('title') . '%');
                })
                ->orderBy('created_at', 'DESC');
        }));
    }


    public function store($request)
    {
      
        if($request->hasFile('image')){
            $path = public_path('exam/exam-banner');
            !is_dir($path) &&
                mkdir($path, 0777, true);
    
            $imageName = "jobo_exam_banner_" . time() . '.' . $request->image->extension();
            $request->image->move($path, $imageName);
        }
     
        $exam  = Exam::query()->create([
            'name' => $request->name,
            'title' => $request->title,
            'key' => $request->key,
            'attemps_count' => $request->attemps_count ?? 0,
            'duration' => $request->duration ?? 0,
            'image' => $request->image !== null ? asset('exam/exam-banner/' . $imageName) : null,
            'max_ball' => $request->max_ball ?? null
        ]);
        return $exam?->id  ?? null;
    }
    
    public function showWithQuestion($exam)
    {
        $queastion = ExamQuestionResource::collection($exam->examQuestion()->with('questions')->paginate(request('limit') ?? 20));

        return $queastion;
    }


    public function update($request)
    {
        $data =  Exam::query()->where('id', $request->exam_id)->first();
        if($request->hasFile('image')){
            $path = public_path('exam/exam-banner');
            $filePath = parse_url($data->image, PHP_URL_PATH);
            $filePath = ltrim($filePath, '/');
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
            !is_dir($path) &&
                mkdir($path, 0777, true);
    
            $imageName = "jobo_exam_banner_" . time() . '.' . $request->image->extension();
            $request->image->move($path, $imageName);
        }
     
        $exam  = $data->update([
            'name' => $request->name,
            'title' => $request->title,
            'key' => $request->key,
            'attemps_count' => $request->attemps_count ?? 0,
            'duration' => $request->duration ?? 0,
            'image' => $request->image !== null ? asset('exam/exam-banner/' . $imageName) :  $data->image,
            'max_ball' => $request->max_ball ?? null 
        ]);

        return  [];
    }

    public function delete($exam)
    {
        $filePath = parse_url($exam->image, PHP_URL_PATH);
        $filePath = ltrim($filePath, '/');
        if (File::exists($filePath)) {
            File::delete($filePath);
        }
        $question_id = $exam->examQuestion()->first()['questions_for_exam_id'] ?? null;

        if ($question_id !== null) {
            $question = Question::find($question_id);
            if ($question !== null) {
                $data = ExamQuestionsAdminServices::getInstance()->destroy($question);
            }
        }
        
        $exam->examQuestion()->delete();
        $exam->delete();
        return [];
    }


    public function question_count($type , $exam_id):void
    {
        $exam = Exam::find($exam_id);

        if (!$exam) {
        } else {
            if ($type == 'minus') {
                if ($exam->questions_count > 0) {
                    $exam->questions_count--;
                    $exam->save(); // Save the changes to the database
                }
            } else if ($type == 'plus') {
                $exam->questions_count++;
                $exam->save(); // Save the changes to the database
            }
        }
    }


    
}
