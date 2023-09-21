<?php

namespace App\Models;

use App\Traits\ApiLogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateExam extends Model
{
    use HasFactory;
    use ApiLogActivity;


    protected $table = 'candidate_exams';
    protected $guarded = [];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function candidate()
    {
        return $this->hasOne(Candidate::class, 'id', 'candidate_id');
    }

    // public function exams()
    // {
    //     return $this->hasMany(Exams::class, 'id', 'exam_id');
    // }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exams_candidate_exams', 'candidate_exams_id', 'exams_id');
    }
}
