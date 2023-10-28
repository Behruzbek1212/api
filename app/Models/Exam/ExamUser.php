<?php


namespace App\Models\Exam;

use App\Models\Candidate;
use App\Models\Exam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ExamUser extends Model
{
    use HasFactory;
    protected $table = 'exam_users';
    protected $guarded = [];

    const FOLDER = 'exam_users';
    const EXAM_USER_START = 'start';
    const EXAM_USER_END = 'end';
    const CONDITION = 2;


    public function exam()
    {
        return $this->hasOne(Exam::class, 'id', 'exam_id');
    }

    // public function exam_student_reports()
    // {
    //     return $this->hasMany(ExamStudentReport::class);
    // }

    public function candidate()
    {
        return $this->hasOne(Candidate::class, 'user_id', user()->id);
    }

    // public function examQuestion()
    // {
    //     return $this->hasOne(ExamQuestion::class, 'exam_id', 'exam_id');
    // }

    public function examQuestions()
    {
        return $this->hasMany(ExamQuestion::class, 'exam_id', 'exam_id');
    }


    // public function subjectTask()
    // {
    //     return $this->hasOne(SubjectTask::class, 'id', 'subject_task_id');
    // }

    // public function subjectTaskTest()
    // {
    //     return $this->hasOne(SubjectTask::class, 'id', 'subject_task_id')->whereHas('taskType', function ($query) {
    //         $query->where('key', 'test');
    //     });
    // }

    // public function studentAnswer()
    // {
    //     return $this->hasOne(StudentAnswer::class, 'exam_student_id', 'id');
    // }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class, 'exam_user_id', 'id');
    }

    public function userAnswerTrue()
    {
        return $this->hasMany(UserAnswer::class, 'exam_user_id', 'id');
    }

    // public function academicGroup()
    // {
    //     return $this->hasOne(AcademicGroup::class, 'id', 'academic_group_id');
    // }
}
