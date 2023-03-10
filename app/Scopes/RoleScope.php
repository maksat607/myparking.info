<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class RoleScope implements Scope
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if(auth()->user()&&!auth()->user()->hasRole(['SuperAdmin'])) {
            $builder->whereNotIn('name', ['SuperAdmin', 'Admin', 'Partner', 'PartnerOperator']);
        }


    }
}
