<?php

namespace App\Filters;

use Illuminate\Support\Facades\DB;

class JobFilter extends QueryFilter
{
    public function title(string $data)
    {
        return $this->builder->when($data, function ($query) use ($data) {
            $query->where('jobs.title', 'LIKE', '%' . $data . '%');
        });
    }

    public function work_type($data)
    {
        return $this->builder->when($data, function ($query) use ($data) {
            $query->where('work_type', '=', $data);
        });
    }

    public function location_id($data)
    {
        return $this->builder->when($data, function ($query) use ($data) {
            $query->isLocation($data);
        });
    }

    public function category_id($data)
    {
        return $this->builder->when($data, function ($query) use ($data) {
            $query->where('category_id', '=', $data);
        });
    }

    public function lesson_activity_id($data)
    {
        return $this->builder->when($data, function ($query) use ($data) {
            $query->whereHas('subjectTopic', function ($query) use ($data) {
                $query->where('lesson_activity_id', $data);
            });
        });
    }

    public function salary($data)
    {
        return $this->builder->when($data, function ($query) use ($data) {
            $query->where('jobs.salary->amount', $data);
        });
    }

    public function start($data)
    {
        return $this->builder->when($data, function ($query) use ($data) {
            $query->where('salary->amount', '>=', $data);
        });
    }


    public function end($data)
    {
        return $this->builder->when($data, function ($query) use ($data) {
            $query->where('salary->amount', '<=', $data);
        });
    }


    public function currency($data)
    {
        return $this->builder->when($data, function ($query) use ($data) {
            $query->whereJsonContains('salary->currency', $data);
        });
    }

    public function sphere($data)
    {
        return $this->builder->when($data, function ($query) use ($data) {
            $query->whereJsonContains('sphere', $data);
        });
    }
}
