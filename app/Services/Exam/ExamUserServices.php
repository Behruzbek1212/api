<?php

namespace App\Services\Exam;

use App\Repository\Exam\ExamUserRepository;
use Illuminate\Database\Eloquent\Builder;

class ExamUserServices
{
    public $repository;

    public function __construct(ExamUserRepository $data)
    {
        $this->repository = $data;
    }

    public static function getInstance(): ExamUserServices
    {
        return new static(ExamUserRepository::getInctance());
    }

    public function list()
    {
        return $this->repository->list(function (Builder $builder) {
            return $builder->where('user_id', user()->id)
                ->where('exam_id', request('exam_id'));
        });
    }
}
