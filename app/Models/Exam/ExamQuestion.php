<?php


namespace App\Models\Exam;


use App\Models\Exam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ExamQuestion extends Model
{
    use HasFactory;
    protected $table = 'exam_questions_for_exam';
    protected $guarded = [];


    public function exam()
    {
        return $this->hasOne(Exam::class, 'id','exam_id');
    }

    public function question()
    {
        return $this->hasOne(Question::class, 'id','questions_for_exam_id')->with(['answerVariants']);
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'id','questions_for_exam_id');
    }

    public function answerVariants()
    {
        return $this->hasMany(AnswerVariant::class, 'question_id','question_id');
    }

}
