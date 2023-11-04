<?php

namespace App\Models;

use App\Models\Exam\ExamQuestion;
use App\Models\Exam\ExamUser;
use App\Traits\ApiLogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory;
    protected $table = 'exams';
    protected $guarded = [];


    public function academicGroupExam()
    {
        return $this->hasOne(AcademicGroupExam::class, 'exam_id', 'id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'employee_id');
    }

    public function semester()
    {
        return $this->hasOne(Semester::class, 'id', 'semester_id');
    }

    public function examUser()
    {
        return $this->hasOne(ExamUser::class, 'exam_id', 'id');
    }

    public function checkExamUser()
    {
        return $this->hasOne(ExamUser::class, 'exam_id', 'id')->where('user_id', user()->id);
    }

    public function examQuestion():HasMany
    {
       return $this->hasMany(ExamQuestion::class);
    }
    
    public function candidate_exams()
    {
        return $this->belongsToMany(CandidateExam::class, 'exams_candidate_exams', 'exams_id', 'candidate_exams_id');
    }
}
