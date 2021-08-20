<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Application extends Model
{
    protected $fillable = [

        'external_id', 'internal_id', 'courier_fullname', 'courier_phone', 'parking_place_number', 'parking_car_sticker', 'arriving_method', 'arriving_interval', 'arriving_at','arrived_at', 'issued_at',

        'partner_id', 'parking_id', 'region_id', 'presentation_id', 'presentation_contract_id', 'courier_type_id', 'tow_truck_payment_id', 'orgn', 'client_id', 'responsible_user_id', 'created_user_id', 'accepted_by', 'issued_by', 'status_id',

        'car_title', 'vin', 'license_plate', 'sts', 'pts', 'pts_type', 'pts_provided', 'sts_provided', 'car_key_quantity', 'year', 'milage', 'owner_number', 'color', 'price', 'on_sale', 'favorite', 'returned', 'services', 'exterior_damage', 'interior_damage', 'condition_gear',  'condition_engine',  'condition_electric',  'condition_transmission',  'car_additional',

        'car_type_id', 'car_mark_id', 'car_model_id', 'car_generation_id', 'car_series_id', 'car_series_body', 'car_modification_id', 'car_gear_id', 'car_engine_id', 'car_transmission_id'

    ];
    protected $dates = ['arriving_at', 'arrived_at', 'issued_at'];
    protected $appends = ['formated_created_at', 'formated_updated_at', 'formated_arriving_at', 'formated_arrived_at', 'formated_issued_at', 'vin_array'];
    
    protected $casts = [
        'condition_engine' => 'array',
        'condition_electric' => 'array',
        'condition_gear' => 'array',
        'condition_transmission' => 'array',
    ];

    public function attachments()
    {
        return $this->morphMany(Attachment::class,'attachable');
    }

    public function parking()
    {
        return $this->belongsTo(Parking::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function carType()
    {
        return $this->belongsTo(CarType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function acceptedBy()
    {
        return $this->belongsTo(User::class, 'accepted_by');
    }

    public function createdUser()
    {
        return $this->belongsTo(User::class, 'created_user_id');
    }

    public function carMark()
    {
        return $this->belongsTo(CarMark::class, 'car_mark_id');
    }

    public function carModel()
    {
        return $this->belongsTo(CarModel::class, 'car_model_id');
    }

    public function issueAcceptions()
    {
        return $this->hasOne(IssueAcception::class, 'application_id');
    }

    public function acceptions()
    {
        return $this->issueAcceptions()->where('is_issue', false);
    }
    public function issue()
    {
        return $this->issueAcceptions()->where('is_issue', true);
    }

    public function getFormatedCreatedAtAttribute($value)
    {
        return isset($this->created_at) && !is_null($this->created_at) ? $this->created_at->format('d-m-Y') : null;
    }

    public function getCurrentParkingCostAttribute($value)
    {
        $endDate = Carbon::now();
        if (!empty( $this->arrived_at ) ) {
            $endDate = !empty( $this->issued_at ) ? $this->issued_at : $endDate;
            $regularPrice = isset($this->pricing['regular_price']) ? $this->pricing['regular_price'] : 500;
            $discountPrice = isset($this->pricing['discount_price']) ? $this->pricing['discount_price'] : 500;
            $discountDays = isset($this->pricing['free_days']) ? $this->pricing['free_days'] : 0;

            $days = $this->arrived_at->diff($endDate->endOfDay())->days + 1;
            $this->parked_days_total = $days;
            $this->parked_days_regular = ($days - $discountDays >= 0) ? $days - $discountDays : 0;
            $this->parked_days_discount = ($days >= $discountDays) ? $discountDays : $discountDays - $days;

            $this->parked_price_discount = ($days >= $discountDays) ? $discountDays * $discountPrice : ($discountDays - $days) * $discountPrice;
            $this->parked_price_regular = ($days - $discountDays >= 0) ? ($days - $discountDays)  * $regularPrice : 0;
            $this->parked_price_total = $this->parked_price_discount + $this->parked_price_regular;
        }
        return 0;
    }

    public function parkingCostInDateRange($startDate, $endDate)
    {
        $test = Carbon::createFromDate('2021', '01', '06');
        $arrivedAt = Carbon::createFromFormat('Y-m-d H:i:s', $this->arrived_at)->startOfDay();
        $issuedAt = isset($this->issued_at) ? Carbon::createFromFormat('Y-m-d H:i:s', $this->issued_at)->endOfDay() : $endDate;
        $price = isset($this->discount_price) ? $this->discount_price : 500;

        $this->start = $arrivedAt <= $startDate ? $startDate : $arrivedAt;
        $this->end = $issuedAt <= $endDate ? $issuedAt : $endDate;
        $this->parked_days = $this->end->diffInDays($this->start) + 1;
        $this->parked_price = $this->parked_days * $price;
    }

    public function getDuplicates()
    {
        $duplicateIDs = null;
        $vinDuplicates = DB::table('applications')
            ->selectRaw('GROUP_CONCAT(id) as ids')
            ->where('vin', $this->vin)
            ->where('vin', '<>', '')
            ->whereNotNull('vin')
            ->groupBy('vin');

        $duplicates = DB::table('applications')
            ->selectRaw('GROUP_CONCAT(id) as ids')
            ->where('license_plate', $this->license_plate)
            ->where('license_plate', '<>', '')
            ->whereNotNull('license_plate')
            ->groupBy('license_plate')
            ->union($vinDuplicates)
            ->get()->toArray();

        $ids = isset($duplicates[0]->ids) ? explode(',', $duplicates[0]->ids) : [];
        $ids = isset($duplicates[1]->ids) ? array_unique(array_merge($ids, explode(',', $duplicates[1]->ids))) : $ids;
        return Application::whereIn('id', $ids);
    }
    public function getVinArrayAttribute()
    {
        return explode(',', $this->vin);
    }

    public function getFormatedUpdatedAtAttribute($value)
    {
        return isset($this->updated_at) && !is_null($this->updated_at) ? $this->updated_at->format('d.m.Y') : 'Не указана';
    }

    public function getFormatedArrivingAtAttribute($value)
    {
        return isset($this->arriving_at) && !is_null($this->arriving_at) ? $this->arriving_at->format('d.m.Y') : 'Не указана';
    }

    public function getFormatedArrivedAtAttribute($value)
    {
        return isset($this->arrived_at) && !is_null($this->arrived_at) ? $this->arrived_at->format('d.m.Y') : 'Не указана';
    }

    public function getFormatedIssuedAtAttribute($value)
    {
        return isset($this->issued_at) && !is_null($this->issued_at) ? $this->issued_at->format('d.m.Y') : 'Не указана';
    }
}