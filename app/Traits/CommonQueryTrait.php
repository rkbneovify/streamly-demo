<?php

namespace App\Traits;

trait CommonQueryTrait
{


    public function scopeStatusFilter($query, $status)
    {
        if ($status === null) {
            return $query;
        }
        return $query->where('status', $status);
    }
    public function scopeDateOrderFilter(
        $query,
        ?string $direction = 'desc',
        string $column = 'created_at'
    ) {
        if ($direction === null) {
            return $query;
        }

        $direction = strtolower($direction);

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }
        return  $query->orderBy($column, $direction);
    }

    public function scopeSearchFilter(
        $query,
        ?string $txt,
        string $column = 'content'
    ) {
        if ($txt === null) {
            return $query;
        }

        return  $query->where($column, 'like', '%' . trim($txt) . '%');
    }
}
