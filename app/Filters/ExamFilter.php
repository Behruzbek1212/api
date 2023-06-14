<?php

namespace App\Filters;

use Illuminate\Support\Facades\DB;

class ExamFilter extends QueryFilter
{
    public function text($data)
    {   
        return $this->builder->when($data, function ($query) use ($data) {
            $query->whereHas('employee', function ($query) use ($data) {
                $query->where(DB::raw("concat(surname,' ',name,' ',patronymic)"), 'ilike','%'.$data.'%');
            });
            $query->orwhereHas('subject', function ($query) use ($data) {
                $query->where('name', 'ilike', '%' . $data . '%');
            });
        });
    }


    public function subject_id($data)
    {
        return $this->builder->when($data, function ($query) use ($data) {
                $query->whereHas('subject', function ($query) use ($data) {
                $query->where('id',$data);
                   
            });
        });
    }

    public function semester_id($data)
    {
        // dd($data);
            return $this->builder->when($data, function ($query) use ($data) {
                $query->whereHas('semester', function ($query) use ($data) {
                $query->where('id',$data); 
            });
        });
    }
}