<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'rank',
        'status'
    ];

    public function partners()
    {
        return $this->hasMany(Partner::class, 'parent_type_id', 'id');
    }
}
