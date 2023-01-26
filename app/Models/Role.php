<?php

namespace App\Models;

use App\Scopes\RoleScope;
use \Spatie\Permission\Models\Role as Model;

class Role extends Model
{

    protected static function booted()
    {
        static::addGlobalScope(new RoleScope);
    }
}
