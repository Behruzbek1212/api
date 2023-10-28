<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Question extends Model
{
    use HasFactory;
    protected $table = 'questions_for_exam';
    protected $guarded = [];


    public function answerVariants()
    {
        return $this->hasMany(AnswerVariant::class, 'questions_for_exam_id','id');
    }
}
