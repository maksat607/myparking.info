<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function carType()
    {
        return $this->belongsTo(CarType::class);
    }

    public function scopePartner($query, $partner_id)
    {
        return $query->where('partner_id', $partner_id);
    }

}
