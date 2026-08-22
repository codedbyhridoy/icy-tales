<?php

namespace App\Models;

use App\Filters\V1\QueryFilter;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    public function guardName(): string
    {
        return 'web';
    }

    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }
}
