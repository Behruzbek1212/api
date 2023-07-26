<?php

namespace App\Filters;

use Illuminate\Support\Facades\DB;

class TransactionHistoryFilter extends QueryFilter
{
    public function name(string $data)
    {
        return $this->builder->when($data, function ($query) use ($data) {
            $query->where('name', 'LIKE', '%' . $data . '%');
        });
    }

    public function trafic_name($data)
    {
        return $this->builder->when($data, function ($query) use ($data) {
            $query->whereHas('trafic', function ($query) use ($data) {
                $query->where('name', $data);
            });
        });
    }
}
