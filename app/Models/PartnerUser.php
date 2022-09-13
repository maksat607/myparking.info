<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerUser extends Model
{
    use HasFactory;
    protected $table='partner_user';
    protected $fillable = ['user_id','partner_id'];

}
