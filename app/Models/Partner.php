<?php

namespace App\Models;

use App\Filter\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;

class Partner extends Model
{
    use HasFactory, Notifiable, Sortable;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'rank',
        'partner_type_id',
        'status',
        'shortname',
        'base_type',
        'inn',
        'kpp',
        'user_id',
        'created_user_id',
        'moderation'
    ];
    public $sortable = ['shortname', 'name', 'inn', 'kpp', 'base_type','partner_type_id'];
    public function partnerType()
    {
        return $this->belongsTo(PartnerType::class, 'partner_type_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function users(){
        return $this->hasManyThrough(
            User::class,//deplo
            PartnerUser::class,//env
            'partner_id', // Foreign key on the environments table...
            'id', // Foreign key on the deployments table...
            'id', // Local key on the projects table...
            'user_id' // Local key on the environments table...
        );
    }

    public function parkings(){
        $arr = collect([]);
        $this->users()->each(function ($item) use (&$arr){
            if ($item->hasRole(['SuperAdmin'])) {
                $arr = $arr->merge(new Parking());
            }
            $arr = $arr->merge($item->parkings);
        });
        return $arr->unique('id');
    }

    public function created_user()
    {
        return $this->belongsTo(User::class, 'created_user_id', 'id');
    }

    public function pricings()
    {
        return $this->hasMany(Pricing::class, 'partner_id', 'id');
    }

    public function scopePartners($query)
    {
        if (auth()->user()->hasRole(['Partner'])) {
            return $query->where('id', auth()->user()->partner->id);
        } elseif (auth()->user()->hasRole(['PartnerOperator'])) {
            return $query->where('id', auth()->user()->owner->partner->id);
        }
        return $query;
    }
    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }
//    public function
}
