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
    public function getColorClass() {
        $color = 'blue';
        if($this->code == 'storage') {
            $color = 'green';
        } elseif ($this->code != 'storage' && $this->code != 'draft') {
            $color = 'pink';
        }
        return $color;
    }
}
