<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    protected $fillable = ['discount_price', 'regular_price', 'free_days', 'partner_id', 'car_type_id'];

    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id', 'id');
    }
    public function carType()
    {
        return $this->belongsTo(CarType::class, 'car_type_id', 'id');
    }
}
