<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerUser extends Model
{
    use HasFactory;
    protected $table='partner_user';
    protected $fillable = ['user_id','partner_id','active'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function partner(){
        return $this->belongsTo(Partner::class);
    }
}
