<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AnswerVariant extends Model
{
    use HasFactory;
    protected $table = 'answer_variants';
    protected $guarded = [];


    public function studentAnswer()
    {
        return $this->hasOne(UserAnswer::class, 'answer_variant_id','id');
    }

}
