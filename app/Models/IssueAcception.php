<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueAcception extends Model
{
    use HasFactory;

    protected $table = 'issue_acceptions';
    protected $guarded = [];

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function scopeIssuance($query)
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
                ->whereIn('user_id', $operatorWithOwnerId)
                ->where('parking_id', $authUser->parkings()->get()->modelKeys());
        }
    }
}
