<?php

namespace App\Models;

use App\Traits\ApiLogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;
    use ApiLogActivity;


    protected $table = 'exams';
    protected $guarded = [];

    // public function trafic_price()
    // {
    //     return $this->hasOne(TraficPrice::class, 'id', 'trafic_price_id');
    // }

    public function candidate_exams()
    {
        return $this->belongsToMany(CandidateExam::class, 'exams_candidate_exams', 'exams_id', 'candidate_exams_id');
    }
}
