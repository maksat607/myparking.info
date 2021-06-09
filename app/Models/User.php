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

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->with(['roles', 'owner']);
    }

    public function legals()
    {
        return $this->hasMany(Legal::class, 'user_id', 'id')->with(['owner']);
    }

    public function getRole()
    {
        $roles = $this->getRoleNames()->toArray();
        return array_shift($roles);
    }

    public function scopeUsers($query)
    {
        if(auth()->user()->hasRole('SuperAdmin')) {
            return $query->whereNull('parent_id')->with(['roles'])->withCount('children');
        } elseif (auth()->user()->hasRole(['Moderator', 'Operator'])) {
            return $query->where('parent_id', auth()->user()->parent_id)->where('id', '<>', auth()->id())->with(['roles']);
        } else {
            return $query->where('parent_id', auth()->id())->with(['roles']);
        }
        return $query;
    }

    public function scopeUser($query, $id)
    {
        if(auth()->user()->hasRole('Admin')) {
            return $query->where('id', $id)->where('parent_id', auth()->user()->id);
        } else {
            return $query->where('id', $id);
        }
        return $query;
    }
}
