<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = ['url', 'name', 'thumbnail_url', 'attachable_id', 'attachable_type', 'file_type'];

    public function attachable()
    {
        return $this->morphTo();
    }

}
