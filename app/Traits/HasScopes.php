<?php

namespace App\Traits;

use App\Models\Location;
use Illuminate\Database\Eloquent\Builder;

trait HasScopes {
    /**
     * @param Builder $query
     * @param int $location_id
     * @return Builder
     */
    public function scopeIsLocation(Builder $query, int $location_id): Builder
    {
        $locations = Location::query();

        if ($location_id == 100000):
            $locations->where('id', '<=', 9999);
        else:
            $locations->where('parent_id', '=', $location_id);
        endif;

        return $query->where(function (Builder $query) use ($location_id, $locations) {
            $query->where('location_id', '=', $location_id);
            foreach ($locations->get() as $location):
                $query->orWhere('location_id', '=', $location->id);
            endforeach;
        });
    }
}
