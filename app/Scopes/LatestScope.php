<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

//Global scopes are dangerous to use most of the time due to relationships creating
//ANSI SQL violations by having Order By when using aggregate function on non-aggregated columns
class LatestScope implements Scope {
    public function apply(Builder $builder, Model $model)
    {
        $builder->orderBy($model::CREATED_AT, 'desc');
    }
}
