<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationHasPending extends Model
{
    use HasFactory;
    protected $fillable = ['application_id','user_id'];
}
