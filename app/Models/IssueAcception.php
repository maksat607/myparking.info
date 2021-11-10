<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueAcception extends Model
{
    use HasFactory;

    protected $table = 'issue_acceptions';
    protected $dates = ['arriving_at'];
    protected $guarded = [];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function getFormatedArrivingAtAttribute()
    {
        return isset($this->arriving_at) ? $this->arriving_at->format('d-m-Y') : null;
    }

    public function scopeIssuances($query)
    {
        $authUser = auth()->user();
        if($authUser->hasRole(['Admin'])) {
            $childrenWithOwnerId = $authUser->children()->without('owner')->get()->modelKeys();
            $childrenWithOwnerId[] = $authUser->id;
            return $query->whereIn('user_id', $childrenWithOwnerId);
        } elseif ($authUser->hasRole(['Manager', 'Operator'])) {
            $operatorWithOwnerId = $authUser->owner->children()->without('owner')->role('Operator')->get()->modelKeys();
            $operatorWithOwnerId[] = $authUser->owner->id;
            return $query
                ->whereIn('user_id', $operatorWithOwnerId);
        }
        return $query;
    }

    public function scopeIssuance($query, $id)
    {
        $authUser = auth()->user();
        if($authUser->hasRole(['Admin'])) {
            $childrenWithOwnerId = $authUser->children()->without('owner')->get()->modelKeys();
            $childrenWithOwnerId[] = $authUser->id;
            return $query
                ->whereIn('user_id', $childrenWithOwnerId)
                ->where('id', $id);
        } elseif ($authUser->hasRole(['Manager', 'Operator'])) {
            $operatorWithOwnerId = $authUser->owner->children()->without('owner')->role('Operator')->get()->modelKeys();
            $operatorWithOwnerId[] = $authUser->owner->id;
            return $query
                ->whereIn('user_id', $operatorWithOwnerId)
                ->where('id', $id);
        }
    }
}
