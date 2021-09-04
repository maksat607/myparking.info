<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Parking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'code',
        'address',
        'timezone',
    ];

    public function legals()
    {
        return $this->belongsToMany(Legal::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function scopeParkings($query)
    {
        if(auth()->user()->hasRole(['Admin'])) {
            $childrenIds = auth()->user()->children()->without('owner')->get()->modelKeys();
            $childrenIds[] = auth()->user()->id;
            $childrenWithOwnerId = $childrenIds;
            return $query->whereIn('user_id', $childrenWithOwnerId);
        } elseif (auth()->user()->hasRole(['Manager', 'Operator'])) {
            return $query->where('user_id', auth()->user()->id);
        }
        return $query;

    }

    public function scopeParking($query, $id)
    {
        if(auth()->user()->hasRole(['Admin'])) {
            $childrenIds = auth()->user()->children()->without('owner')->get()->modelKeys();
            $childrenIds[] = auth()->user()->id;
            $childrenWithOwnerId = $childrenIds;
            return $query->where('id', $id)
                ->whereIn('user_id', $childrenWithOwnerId);
        } elseif (auth()->user()->hasRole(['Manager', 'Operator'])) {
            return $query->where('id', $id)
                ->where('user_id', auth()->user()->id);
        }
        return $query->where('id', $id);
    }
}
