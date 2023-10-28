<?php


namespace App\Models\Exam;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UserAnswer extends Model
{
    use HasFactory;
    protected $table = 'user_answers';
    protected $guarded = [];


    public function question()
    {
        return $this->hasOne(Question::class, 'id', 'questions_for_exam_id');
    }

    public function answerVariant()
    {
        return $this->hasOne(AnswerVariant::class, 'id', 'answer_variant_id');
    }


    public function answerVariants()
    {
        return $this->hasMany(AnswerVariant::class, 'id', 'answer_variant_id');
    }

    public function examUsers()
    {
        return $this->hasMany(ExamUser::class, 'id', 'exam_user_id');
    }


    public function examStudent()
    {
        return $this->hasOne(ExamUser::class, 'id', 'exam_user_id');
    }

}
