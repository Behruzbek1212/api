<?php

namespace App\Filters;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class CandidateFilter extends QueryFilter
{
    public function name($data)
    {
        return $this->builder->when($data, function ($query) use ($data) {
            $query->where(DB::raw("concat(name,' ',surname)"), 'like', '%' . $data . '%');
        });
    }


    public function title($data)
    {
        return $this->builder->when($data, function ($query) use ($data) {
            $query->whereHas('user.resumes', function (Builder $query) use ($data) {
                $query->whereRaw(
                    'lower(json_unquote(json_extract(`data`, \'$."position"\'))) like ?',
                    ['%' . strtolower($data) . '%']
                );
            });
        });
    }

    public function sphere($data)
    {
        return $this->builder->when($data, function ($query) use ($data) {
            $query->whereJsonContains('spheres', $data);
        });
    }
}
