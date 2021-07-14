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
    ];

    public function partnerType()
    {
        return $this->belongsTo(PartnerType::class, 'partner_type_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
