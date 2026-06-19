<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (auth()->hasUser() && auth()->user()->isOrganizer()) {
            $organizer = auth()->user()->organizer;
            if ($organizer) {
                $builder->where($model->getTable().'.organizer_id', $organizer->id);
            } else {
                // If the user is an organizer but has no profile yet, return nothing.
                $builder->whereRaw('1 = 0');
            }
        }
    }
}
