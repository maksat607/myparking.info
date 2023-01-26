<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Legal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'reg_number',
        'inn',
        'kpp',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function parkings()
    {
        return $this->belongsToMany(Parking::class);
    }

}
