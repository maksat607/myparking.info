<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

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
}
