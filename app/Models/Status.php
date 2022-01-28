<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['name', 'color','is_active', 'rank'];
    protected $appends = ['can_view', 'can_assign'];

    public static function viewableStatuses() {
        foreach (auth()->user()->getAllPermissions() as $key => $permission) {
            if ( strpos( $permission->name, 'status-can-view__') !== false ) {
                $viewableStatuses[] = str_replace('status-can-view__', '', $permission->name);
            }
        }
        return Status::whereIn('code' ,$viewableStatuses)->get();
    }

    public static function setableStatuses() {
        foreach (auth()->user()->getAllPermissions() as $key => $permission) {
            if ( strpos( $permission->name, 'status-can-assign__') !== false ) {
                $setableStatuses[] = str_replace('status-can-assign__', '', $permission->name);
            }
        }
        return Status::whereIn('code' ,$setableStatuses)->get();
    }

    public function getCanViewAttribute($value)
    {
        if (auth()->check()) {
            $permissions = array_column(auth()->user()->getAllPermissions()->toArray(), 'name');
            return in_array('status-can-view__' . $this->code, $permissions);
        }
        return false;

    }
    public function getCanAssignAttribute($value)
    {
        if (auth()->check()) {
            $permissions = array_column(auth()->user()->getAllPermissions()->toArray(), 'name');
            return in_array('status-can-assign__' . $this->code, $permissions);
        }
        return false;
    }
    public function getStatusSortAttribute() {
        if ($this->id == 2) {
            return 'arrived_at';
        }
        if ($this->id == 3) {
            return 'issued_at';
        }
        if ($this->id == 6) {
            return 'created_at';
        }

        return 'arriving_at';
    }
    public function getColorClass() {
        $color = 'status-primary';
        if($this->code == 'storage') {
            $color = 'status-success';
        } elseif ($this->code != 'storage' && $this->code != 'draft') {
            $color = 'status-danger';
        }
        return $color;
    }

    public function scopeStatuses($query, $acceptions = null)
    {

        if(auth()->user()->hasRole(['Manager', 'Admin', 'SuperAdmin']) && $acceptions) {
            return $query->where('code', '<>', 'draft')->orderBy('name', 'asc');
        } elseif (auth()->user()->hasRole(['Manager', 'Admin', 'SuperAdmin'])) {
            return $query->orderBy('name', 'asc');
        } else {
            return $query->whereIn('code', ['draft', 'pending'])->orderBy('name', 'asc');
        }
        return $query->orderBy('name', 'asc');
    }
}
