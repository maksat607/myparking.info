<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryFile extends Model
{
    use HasFactory;

    protected $fillable = ['token'];

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
    public function attachable()
    {
        return $this->morphTo();
    }
}
