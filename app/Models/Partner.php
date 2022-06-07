<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Partner extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'rank',
        'partner_type_id',
        'status',
        'shortname',
        'base_type',
        'inn',
        'kpp',
    ];

    public function partnerType()
    {
        return $this->belongsTo(PartnerType::class, 'partner_type_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
//    public function
    public function pricings()
    {
        return $this->hasMany(Pricing::class, 'partner_id', 'id');
    }

    public function scopePartners($query)
    {
        if (auth()->user()->hasRole(['Partner'])) {
            return $query->where('id', auth()->user()->partner->id);
        } elseif (auth()->user()->hasRole(['PartnerOperator'])) {
            return $query->where('id', auth()->user()->owner->partner->id);
        }
        return $query;
    }
}
