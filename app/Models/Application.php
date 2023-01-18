<?php

namespace App\Models;

use App\Filter\QueryFilter;
use App\Notifications\CarStatusChangeNotification;
use App\Services\TimeIntervalIntersection;
use App\Traits\NotifyApplicationChanges;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Application extends Model
{
    use HasFactory;
    use Notifiable;
    use NotifyApplicationChanges;
    use \Awobaz\Compoships\Compoships;

    protected $fillable = [

        'external_id', 'internal_id', 'courier_fullname', 'courier_phone', 'parking_place_number', 'parking_car_sticker', 'arriving_method', 'arriving_interval', 'arriving_at', 'arrived_at', 'issued_at',

        'partner_id', 'parking_id', 'region_id', 'presentation_id', 'presentation_contract_id', 'courier_type_id', 'tow_truck_payment_id', 'orgn', 'client_id', 'responsible_user_id', 'created_user_id', 'accepted_by', 'issued_by', 'status_id',

        'car_title', 'vin', 'license_plate', 'sts', 'pts', 'pts_type', 'pts_provided', 'sts_provided', 'car_key_quantity', 'year', 'milage', 'owner_number', 'color', 'price', 'on_sale', 'favorite', 'returned', 'services', 'exterior_damage', 'interior_damage', 'condition_gear', 'condition_engine', 'condition_electric', 'condition_transmission', 'car_additional',

        'car_type_id', 'car_mark_id', 'car_model_id', 'car_generation_id', 'car_series_id', 'car_series_body', 'car_modification_id', 'car_gear_id', 'car_engine_id', 'car_transmission_id', 'free_parking', 'rejected_by', 'deleted_by', 'approved'
    ];

    protected $dates = ['arriving_at', 'arrived_at', 'issued_at'];
    protected $appends = [
        'formated_created_at',
        'formated_updated_at',
        'formated_arriving_at',
        'formated_arrived_at',
        'formated_issued_at',
        'vin_array',
    ];

    protected $casts = [
        'condition_engine' => 'array',
        'condition_electric' => 'array',
        'condition_gear' => 'array',
        'condition_transmission' => 'array',
        'favorite' => 'boolean',
        'returned' => 'boolean'
    ];
     public static function boot()
    {

        parent::boot();


        static::retrieved(function ($item) {
        });
        static::saving(function ($item) {
            $item->vin = mb_strtoupper($item->vin) == mb_strtoupper("не указан") ? null : mb_strtoupper($item->vin);
            $item->license_plate = mb_strtoupper($item->license_plate) == mb_strtoupper("не указан") ? null : mb_strtoupper($item->license_plate);

//            dump($item->status->id);
//            dd($item->status_id);
            if (
                !$item->ApplicationHasPending &&
                $item->status_id == self::$appStatuses['Хранение'] &&
                $item->partner->moderation == 1 &&
                isset($item['id']) &&
                ($item->status->id != $item->status_id) ||
                $item->status_id == self::$appStatuses['Модерация']
            ) {
                ApplicationHasPending::firstOrCreate(['application_id' => $item->id, 'user_id' => auth()->user()->id]);
                $item->status_id = self::$appStatuses['Модерация'];
                $item->arrived_at = null;
            }
        });
        static::updated(function ($item) {

            if (($item->status->id != $item->status_id)) {
                $data = [];

                $message = new Message($item);
                $data = $message->applicationMessage;
                if (count($data) > 0) {
                    Notification::send($message->users, new UserNotification($data));
                }
            }
        });
        static::deleted(function ($item) {
        });
        static::created(function ($item) {
            $message = new Message($item);
            $data = [];
            if ($item->status_id == self::$appStatuses['Хранение'] && $item->partner->moderation == 1) {
                ApplicationHasPending::firstOrCreate(['application_id' => $item->id, 'user_id' => auth()->user()->id]);
                $item->status_id = self::$appStatuses['Модерация'];
                $item->arrived_at = null;
                $item->save();
            }
            if ($item->status_id == self::$appStatuses['Ожидает принятия'] || $item->status_id == self::$appStatuses['Хранение']) {
                $data = $message->applicationMessage;
            }
            if (count($data) > 0) {
                Notification::send($message->users, new UserNotification(($data)));
            }
        });
    }
    protected $with = ['issueAcceptions', 'status', 'acceptions','parking','partner'];

    public function getZeroAttribute()
    {
        return 0;
    }

    public function getVinAttribute($value)
    {
        return $value ?? 'не указан';
    }

    public function getLicensePlateAttribute($value)
    {
        return $value ?? 'не указан';
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class)->withDefault();
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
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

    public function removedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function createdUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function carMark()
    {
        return $this->belongsTo(CarMark::class, 'car_mark_id');
    }

    public function carModel()
    {
        return $this->belongsTo(CarModel::class, 'car_model_id');
    }

    public function acceptions()
    {
        return $this->issueAcceptions()->where('is_issue', false);
    }

    public function issueAcceptions()
    {
        return $this->hasOne(IssueAcception::class, 'application_id');
    }

    public function issuance()
    {
        return $this->issueAcceptions()->where('is_issue', true);
    }

    public function viewRequests()
    {
        return $this->hasMany(ViewRequest::class);
    }

    public function free()
    {
        return $this->attributes['free_parking'];
    }

    public function getDefaultAttachmentAttribute()
    {
        return (object)['thumbnail_url' => asset('no-image.png')];
    }

    public function getCurrentParkingCostAttribute($value)
    {
        $endDate = Carbon::now();
        if (!empty($this->arrived_at)) {
            $endDate = !empty($this->issued_at) ? $this->issued_at : $endDate;
            $regularPrice = isset($this->pricing['regular_price']) ? $this->pricing['regular_price'] : 500;
            $discountPrice = isset($this->pricing['discount_price']) ? $this->pricing['discount_price'] : 500;
            $discountDays = isset($this->pricing['free_days']) ? $this->pricing['free_days'] : 0;

            $days = $this->arrived_at->diff($endDate->endOfDay())->days + 1;
            $this->parked_days_total = $days;
            $this->parked_days_regular = ($days - $discountDays >= 0) ? $days - $discountDays : 0;
            $this->parked_days_discount = ($days >= $discountDays) ? $discountDays : $discountDays - $days;

            $this->parked_price_discount = ($days >= $discountDays) ? $discountDays * $discountPrice : ($discountDays - $days) * $discountPrice;
            $this->parked_price_regular = ($days - $discountDays >= 0) ? ($days - $discountDays) * $regularPrice : 0;
            $this->parked_price_total = $this->parked_price_discount + $this->parked_price_regular;
        }
        return 0;
    }

    public function parkingCostInDateRange($startDate, $endDate)
    {
        $price = 0;
        if ($this->basicPrice) {
            $price = $this->basicPrice->regular_price;
        }
        if ($this->parkingBasicPrice) {
            $price = $this->parkingBasicPrice->regular_price;
        }
        if ($this->parkingPartnerPrice) {
            $price = $this->parkingPartnerPrice->regular_price;
        }

        $end = $this->issued_at ?? now();
//        dump($this->arrived_at);
//        dump($end);

//        dd($this);
        $this->parked_days = $this->status_name === "Хранение"
            ? $this->arrived_at->diff(now())->days + 1
            : TimeIntervalIntersection::getDays([$this->arrived_at, $end], [$this->arrived_at, $end]);

//        $this->parked_days = $this->status_id === 2
//            ? $this->arrived_at->diff(now())->days + 2
//            : $this->arrived_at->diff($end)->days + 2;
//        if (!((request()->get('status_id') && request()->get('status_id') == 'instorage') || (count(request()->all()) == 0))) {
//            $this->parked_days = $this->parked_days + 1;
//        }
        if (
            (request()->get('status_id') && request()->get('status_id') != 'instorage')
        ) {
            $this->parked_days_in_period = TimeIntervalIntersection::getDays([$this->arrived_at, $end], [$startDate, $endDate]);
            $this->parked_price_in_period = $this->parked_days_in_period * $price;
        }
        $this->parked_price = $this->attributes['free_parking'] ? "БХ" : $this->parked_days * $price;
//        dd($this->parked_price);
    }

    public function parking()
    {
        return $this->belongsTo(Parking::class);
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

    public function getFormatedCreatedAtAttribute($value)
    {
        return isset($this->created_at) && !is_null($this->created_at) ? $this->created_at->format('d-m-Y') : 'Нет';
    }

    public function getFormatedUpdatedAtAttribute($value)
    {
        return isset($this->updated_at) && !is_null($this->updated_at) ? $this->updated_at->format('d.m.Y') : 'Нет';
    }

    public function getFormatedArrivingAtAttribute($value)
    {
        return isset($this->arriving_at) && !is_null($this->arriving_at) ? $this->arriving_at->format('d.m.Y') : 'Нет';
    }

    public function getFormatedArrivedAtAttribute($value)
    {
        return isset($this->arrived_at) && !is_null($this->arrived_at) ? $this->arrived_at->format('d.m.Y') : 'Нет';
    }

    public function getFormatedIssuedAtAttribute($value)
    {
        return isset($this->issued_at) && !is_null($this->issued_at) ? $this->issued_at->format('d.m.Y') : 'Нет';
    }

    public function scopeApplications($query)
    {
        $authUser = auth()->user();
        if ($authUser->hasRole(['Admin'])) {
            $childrenIds = $authUser->children()->pluck('id')->toArray();
            $childrenIds[] = $authUser->id;
            $parkingsIds = Parking::whereIn('user_id', $childrenIds)->pluck('id')->toArray();
            return $query
                ->whereIn('parking_id', $parkingsIds);
        } elseif ($authUser->hasRole(['Moderator'])) {
            if ($authUser->owner->hasRole('Admin')) {
                $childrenIds = $authUser->owner->children()->pluck('id')->toArray();
                $childrenIds[] = $authUser->id;
                $childrenIds[] = $authUser->owner->id;
            }
            if ($authUser->owner->hasRole('SuperAdmin')) {
                $childrenIds = User::all()->filter(function ($user) {
                    return $user->getRole() == 'Admin';
                })->pluck('id');
            }
            $parkingsIds = Parking::whereIn('user_id', $childrenIds)->pluck('id')->toArray();
            return $query
                ->whereIn('parking_id', $parkingsIds);
        } elseif ($authUser->hasRole(['Partner'])) {
            return $query
                ->where('partner_id', $authUser->partner->id);
        } elseif ($authUser->hasRole(['Manager'])) {
            return $query
                ->whereIn('parking_id', $authUser->managerParkings()->get()->modelKeys());
        } elseif ($authUser->hasRole(['Operator'])) {
            /*$childrenIds = $authUser->owner->children()->without('owner')->role(['Manager'])->pluck('id')->toArray();
            $childrenIds[] = $authUser->id;*/
            $parkingsIds = Parking::where('user_id', $authUser->owner->id)->pluck('id')->toArray();
            return $query
                ->whereIn('parking_id', $parkingsIds);
        } elseif ($authUser->hasRole(['PartnerOperator'])) {
            return $query
                ->where('partner_id', $authUser->owner->partner->id);
        }
        return $query;
    }

    public function scopeApplication($query, $id)
    {

        $authUser = auth()->user();
        if ($authUser->hasRole(['Admin'])) {
            $childrenIds = $authUser->children()->pluck('id')->toArray();
            $childrenIds[] = $authUser->id;
            $parkingsIds = Parking::whereIn('user_id', $childrenIds)->pluck('id')->toArray();
            return $query
                ->where('id', $id)
                ->whereIn('parking_id', $parkingsIds);
        } elseif ($authUser->hasRole(['Partner'])) {
            $childrenIds = $authUser->children()->without('owner')->get()->modelKeys();
            $childrenIds[] = $authUser->id;
            $childrenWithOwnerId = $childrenIds;
            return $query
                ->where('id', $id)//                ->whereIn('user_id', $childrenWithOwnerId)
                ;
        } elseif ($authUser->hasRole(['Manager'])) {
            return $query
                ->where('id', $id)
                ->whereIn('parking_id', $authUser->managerParkings()->get()->modelKeys());
        } elseif ($authUser->hasRole(['Operator'])) {
            /*$childrenIds = $authUser->owner->children()->without('owner')->role(['Manager'])->pluck('id')->toArray();
            $childrenIds[] = $authUser->id;*/
            $parkingsIds = Parking::where('user_id', $authUser->owner->id)->pluck('id')->toArray();
            return $query
                ->where('id', $id)
                ->whereIn('parking_id', $parkingsIds);
        } elseif ($authUser->hasRole(['PartnerOperator'])) {
            $operatorWithOwnerId = $authUser->owner->children()->without('owner')->role(['Operator', 'PartnerOperator'])->get()->modelKeys();
            $operatorWithOwnerId[] = $authUser->owner->id;
            return $query
                ->where('id', $id);
//                ->whereIn('user_id', $operatorWithOwnerId);
        }
        return $query->where('id', $id);
    }

    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }

    public function ApplicationHasPending()
    {
        return $this->hasOne(ApplicationHasPending::class);
    }

    public function partnerNotifications()
    {
        return $this->notifications->filter(
            function ($item) {
                return $item->data['type'] == "partner";
            }
        );
    }

    public function storageNotifications()
    {
        return $this->notifications->filter(
            function ($item) {
                return $item->data['type'] == "storage";
            }
        );
    }


    public function parkingPartnerPrice()
    {
        return $this->hasOne(
            Price::class,
            ['car_type_id', 'parking_id', 'partner_id'],
            ['car_type_id', 'parking_id', 'partner_id']
        );
    }

    public function parkingBasicPrice()
    {
        return $this->hasOne(
            Price::class,
            ['car_type_id', 'parking_id', 'partner_id'],
            ['car_type_id', 'parking_id', 'zero']
        );
    }

    public function basicPrice()
    {
        return $this->hasOne(
            Price::class,
            ['car_type_id', 'parking_id', 'partner_id'],
            ['car_type_id', 'zero', 'zero']
        );
    }
}
