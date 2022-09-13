<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class PartnerType extends Model
{
    use HasFactory,Sortable;

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
    public $sortable = ['name'];
    public function partners()
    {
        return $this->hasMany(Partner::class, 'parent_type_id', 'id');
    }
}
