<?php

namespace App\Filters\V1;

class RoleFilter extends QueryFilter
{
    protected $sortable = [
        'name',
    ];

    public function name($value)
    {
        $likeStr = str_replace('*', '%', $value);

        return $this->builder->where('name', 'like', $likeStr);
    }
}
