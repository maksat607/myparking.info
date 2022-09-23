<?php

namespace App\Models;

use App\Traits\NotifyViewRequestChanges;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ViewRequest extends Model
{
    use HasFactory,NotifyViewRequestChanges;
    protected $fillable = ['client_name', 'organization_name', 'comment', 'arriving_at', 'arriving_interval', 'finished_at', 'status_id', 'application_id', 'created_user_id', 'user_id', 'reviewed_by'];
    protected $dates = ['arriving_at', 'finished_at'];
    protected $appends = ['status_name', 'formated_created_at', 'formated_arriving_at', 'formated_finished_at', 'formated_arriving_interval'];
    protected static $statusLabels = [
        '1'=> 'В ожидании',
        '2'=> 'Осмотрено',
        '3'=> 'Не осмотрено',
    ];
    public function attachments()
    {
        return $this->morphMany(Attachment::class,'attachable');
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id', 'id');
    }
    public function applicationWithParking()
    {

        $query = $this->application;
        $authUser = auth()->user();
        if($authUser->hasRole(['Admin'])) {
            $childrenIds = $authUser->children()->pluck('id')->toArray();
            $childrenIds[] = $authUser->id;
            $parkingsIds = Parking::whereIn('user_id', $childrenIds)->pluck('id')->toArray();
            if(in_array($query->parking_id,$parkingsIds)){
                return $query;
            }

        } elseif($authUser->hasRole(['Partner'])) {
            if($query->partner_id==$authUser->partner->id){
                return $query;
            }

        } elseif ($authUser->hasRole(['Manager'])) {
            if(in_array($query->parking_id,$authUser->managerParkings()->get()->modelKeys())){
                return $query;
            }

        } elseif($authUser->hasRole(['Operator'])) {
            /*$childrenIds = $authUser->owner->children()->without('owner')->role(['Manager'])->pluck('id')->toArray();
            $childrenIds[] = $authUser->id;*/
            $parkingsIds = Parking::where('user_id', $authUser->owner->id)->pluck('id')->toArray();
            if(in_array($query->parking_id,$parkingsIds)){
                return $query;
            }

        } elseif ($authUser->hasRole(['PartnerOperator'])) {
            if($query->partner_id==$authUser->owner->partner->id){
                return $query;
            }
        }
        return false;
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_user_id');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getStatusNameAttribute()
    {
        return $this->statusLabels[$this->status_id];
    }

    public function getFormatedCreatedAtAttribute()
    {
        return isset($this->created_at) ? $this->created_at->format('d-m-Y') : 'Не указана';
    }

    public function getFormatedArrivingAtAttribute()
    {
        return isset($this->arriving_at) ? $this->arriving_at->format('d-m-Y') : 'Не указана';
    }

    public function getFormatedFinishedAtAttribute()
    {
        return isset($this->finished_at) ? $this->finished_at->format('d-m-Y') : 'Не указана';
    }
    public function getFormatedArrivingIntervalAttribute()
    {
        return isset($this->arriving_interval) ? $this->arriving_interval : 'Не указан';
    }

    public static function getStatuses($end = 0, $start = 0)
    {
        $result = [];
        if(($end && $start)) {
            $result = array_slice(self::$statusLabels, $start, $end, true);
        } elseif($start > 0 && $end == 0) {
            $result = array_slice(self::$statusLabels, $start, count(self::$statusLabels)-1, true);
        } else {
            $result = self::$statusLabels;
        }
        return $result;
    }

    public function scopeViewRequests($query)
    {
        $authUser = auth()->user();
        if($authUser->hasRole(['Admin'])) {
            $childrenWithOwnerId = $authUser->children()->without('owner')->get()->modelKeys();
            $childrenWithOwnerId[] = $authUser->id;
            return $query->whereIn('user_id', $childrenWithOwnerId);
        }/* elseif ($authUser->hasRole(['Manager', 'Operator'])) {
            $operatorWithOwnerId = $authUser->owner->children()->without('owner')->role('Operator')->get()->modelKeys();
            $operatorWithOwnerId[] = $authUser->owner->id;
            return $query
                ->whereIn('user_id', $operatorWithOwnerId)
               ->where('parking_id', $authUser->parkings()->get()->modelKeys());
        }*/
        return $query;
    }

    public function scopeViewRequest($query, $id)
    {
        $authUser = auth()->user();
        if($authUser->hasRole(['Admin'])) {
            $childrenWithOwnerId = $authUser->children()->without('owner')->get()->modelKeys();
            $childrenWithOwnerId[] = $authUser->id;
            return $query
                ->whereIn('user_id', $childrenWithOwnerId)
                ->where('id', $id);
        }/* elseif ($authUser->hasRole(['Manager', 'Operator'])) {
            $operatorWithOwnerId = $authUser->owner->children()->without('owner')->role('Operator')->get()->modelKeys();
            $operatorWithOwnerId[] = $authUser->owner->id;
            return $query
                ->whereIn('user_id', $operatorWithOwnerId)
               ->where('parking_id', $authUser->parkings()->get()->modelKeys());
        }*/
        return $query->where('id', $id);
    }
}
