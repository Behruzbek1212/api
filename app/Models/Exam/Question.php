<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;
    protected $table = 'questions_for_exam';
    protected $guarded = [];


    public function answerVariants()
    {
        return $this->hasMany(AnswerVariant::class, 'questions_for_exam_id','id');
    }

    public function examQuestions()
    {
       return $this->belongsTo(ExamQuestion::class, 'questions_for_exam_id', 'id');
    }
}
