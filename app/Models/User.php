<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    protected static function booted()
    {
        static::deleting(function ($user) {
            //$user->children->each->roles()->detach();
            $user->children->each(function ($item, $key) {
                $item->roles()->detach();
            });
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
        'parent_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function owner()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function partnerParkings()
    {

        return $this->belongsToMany(Parking::class);
    }

    public function parkings()
    {
        if($this->hasRole(['SuperAdmin'])){
            return new Parking();
        }
        return $this->hasMany(Parking::class, 'user_id', 'id');
    }
    public function cars(){
        return $this->hasManyThrough(
            Application::class,//dep
            Parking::class,//env

        );
    }

    public function managerParkings()
    {
        return $this->belongsToMany(Parking::class, 'manager_parking', 'manager_id', 'parking_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->with(['roles', 'owner']);
    }

    public function legals()
    {
        return $this->hasMany(Legal::class, 'user_id', 'id')->with(['owner']);
    }

    public function partner()
    {
        return $this->hasOne(Partner::class, 'user_id', 'id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'user_id', 'id');
    }


    public function getRole()
    {
        $roles = $this->getRoleNames()->toArray();
        return array_shift($roles);
    }

    public function getUserOwnerId()
    {
        return isset(auth()->user()->owner) ? auth()->user()->owner->id : auth()->user()->id;
    }

    public function getUsersAdmin()
    {

        return isset(auth()->user()->owner) ?
            array_merge([auth()->user()->owner->id], auth()->user()->owner->children->modelKeys()) :
            array_merge([auth()->user()->id], auth()->user()->children->modelKeys());
    }

    public function scopeUsers($query)
    {
        if(auth()->user()->hasRole('SuperAdmin')) {
            return $query->whereNull('parent_id')->with(['roles'])->withCount('children');
        } elseif (auth()->user()->hasRole(['Manager', 'Operator'])) {
            return $query->where('parent_id', auth()->user()->parent_id)->where('id', '<>', auth()->id())->with(['roles']);
        } else {
            return $query->where('parent_id', auth()->id())->with(['roles']);
        }
        return $query;
    }
    public function scopeUser($query, $id)
    {
        if(auth()->user()->hasRole('Admin|Partner')) {
            return $query->where('id', $id)->where('parent_id', auth()->user()->id);
        } else {
            return $query->where('id', $id);
        }
        return $query;
    }
    public function usersFilter()
    {
        $auth = auth()->user();
        if($auth->hasRole(['SuperAdmin'])) {
            $users = static::where('id', '<>', auth()->id())->get();
            $auth->kids = $users;
            return $auth;
        }
        $auth->kids = $auth->children()->orderBy('name', 'ASC')->get();
        return $auth;
    }
    public function getParentUser()
    {
        if (auth()->user()->hasRole(['Manager', 'Operator', 'PertnerOperator'])) {
            return auth()->user()->parent_id;
        } elseif (auth()->user()->hasRole(['Admin', 'Partner'])) {
            return auth()->user()->id;
        }
        return null;
    }


    public function partners()
    {
        return $this->belongsToMany(Partner::class)->withPivot('active')->wherePivot('active',1);;
    }
}
