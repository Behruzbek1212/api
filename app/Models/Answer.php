<?php

namespace App\Models;

use App\Traits\ApiLogActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    use ApiLogActivity;
    protected $table = 'answers';
    protected $guarded = [];

    public function question()
    {
        return $this->hasOne(Question::class, 'id', 'question_id');
    }

    public function candidate()
    {
        return $this->hasOne(Candidate::class, 'id', 'candidate_id');
    }
}
