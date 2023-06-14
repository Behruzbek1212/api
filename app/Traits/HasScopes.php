<?php

namespace App\Traits;

use Illuminate\Support\Str;
use App\Models\Location;
use Illuminate\Database\Eloquent\Builder;

trait HasScopes
{

    /**
     * @param Builder $query
     * @param int $location_id
     * @return Builder
     */

    public string $key;

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->key = Str::singular($this->getTable());
    }

    public function scopeWhereEqual($query, $tableField, $requestField = null, $request = null): void
    {
        $request = getRequest($request);
        $requestField = $requestField ?? $tableField;
        if (!in_array($request->$requestField, [null, 'undefined']))
            $query->where($tableField, $request->$requestField);
    }

    public function scopeWhereLike($query, $tableField, $requestField = null, $request = null): void
    {
        $request = getRequest($request);
        $requestField = $requestField ?? $tableField;
        if (!in_array($request->$requestField, [null, 'undefined']))
            $query->where($tableField, 'ilike', '%' . $request->$requestField . '%');
    }

    public function scopeOrWhereLike($query, $tableField, $requestField = null, $request = null): void
    {
        $request = getRequest($request);
        $requestField = $requestField ?? $tableField;
        if (!in_array($request->$requestField, [null, 'undefined']))
            $query->orWhere($tableField, 'ilike', '%' . $request->$requestField . '%');
    }

    public function scopeWhereHasLike($query, $relationName, $tableField, $requestField = null, $request = null): void
    {
        $request = getRequest($request);
        $requestField = $requestField ?? $tableField;
        if (!in_array($request->$requestField, [null, 'undefined']))
            $query->whereHas($relationName, function ($query) use ($tableField, $requestField, $request) {
                $query->where($tableField, 'ilike', '%' . $request->$requestField . '%');
            });
    }

    public function scopeWhereHasEqual($query, $relationName, $tableField, $requestField = null, $request = null): void
    {
        $request = getRequest($request);
        $requestField = $requestField ?? $tableField;
        if (!in_array($request->$requestField, [null, 'undefined']))
            $query->whereHas($relationName, function ($query) use ($tableField, $requestField, $request) {
                $query->where($tableField, $request->$requestField);
            });
    }

    public function scopeOrWhereHasLike($query, $relationName, $tableField, $requestField = null, $request = null): void
    {
        $request = getRequest($request);
        $requestField = $requestField ?? $tableField;
        if (!in_array($request->$requestField, [null, 'undefined']))
            $query->orWhereHas($relationName, function ($query) use ($tableField, $requestField, $request) {
                $query->where($tableField, 'ilike', '%' . $request->$requestField . '%');
            });
    }

    public function scopeWhereBetween2($query, $tableField, $request = null): void
    {
        $request = getRequest($request);
        $start = $tableField . '_start';
        $end = $tableField . '_end';
        if ($request->$start) {
            $query->whereDate($tableField, '>=', $request->$start);
        }
        if ($request->$end) {
            $query->whereDate($tableField, '<=', $request->$end);
        }
    }

    public function scopeWhereBetween3($query, $tableField, $request = null): void
    {
        $request = getRequest($request);
        $start = $tableField . '_start';
        $end = $tableField . '_end';
        if ($request->$start) {
            $query->where($tableField, '>=', $request->$start);
        }
        if ($request->$end) {
            $query->where($tableField, '<=', $request->$end);
        }
    }

    public function scopeSort($query): void
    {
        $order = requestOrder();
        $query->orderBy($order['key'], $order['value']);
    }

    public function scopeWhereSearch($query, $fieldNames, $request = null)
    {
        $request = getRequest($request);
        $search = $request->get('search', '');
        $query->where(function ($query) use ($fieldNames, $search) {
            foreach ($fieldNames as $index => $field) {
                $index == 0
                    ? $query->where($field, 'ilike', '%' . $search . '%')
                    : $query->orWhere($field, 'ilike', '%' . $search . '%');
            }
        });
    }

    public function scopeWhereHasSearch($query, $relationName, $fieldNames, $request = null)
    {
        $request = getRequest($request);
        $search = $request->get('search', '');
        $query->whereHas($relationName, function ($query) use ($fieldNames, $search) {
            $query->where(function ($query) use ($fieldNames, $search) {
                foreach ($fieldNames as $index => $field) {
                    $index == 0
                        ? $query->where($field, 'ilike', '%' . $search . '%')
                        : $query->orWhere($field, 'ilike', '%' . $search . '%');
                }
            });
        });
    }

    public function scopeOrWhereHasSearch($query, $relationName, $fieldNames, $request = null)
    {
        $request = getRequest($request);
        $search = $request->get('search', '');
        $query->orWhereHas($relationName, function ($query) use ($fieldNames, $search) {
            $query->where(function ($query) use ($fieldNames, $search) {
                foreach ($fieldNames as $index => $field) {
                    $index == 0
                        ? $query->where($field, 'ilike', '%' . $search . '%')
                        : $query->orWhere($field, 'ilike', '%' . $search . '%');
                }
            });
        });
    }

    public function scopeCustomPaginate($query, $per_page = null, $requestField = 'per_page', $request = null)
    {
        $request = getRequest($request);
        return $query->paginate($request->get($requestField, $per_page ?? self::count()));
    }
    public function scopeIsLocation(Builder $query, int $location_id): Builder
    {
        $locations = Location::query();

        if ($location_id == 100000) :
            $locations->where('id', '<=', 9999);
        else :
            $locations->where('parent_id', '=', $location_id);
        endif;

        return $query->where(function (Builder $query) use ($location_id, $locations) {
            $query->where('location_id', '=', $location_id);
            foreach ($locations->get() as $location) :
                $query->orWhere('location_id', '=', $location->id);
            endforeach;
        });
    }
}
