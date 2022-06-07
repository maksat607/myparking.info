<?php

namespace App\Http\Controllers;

use App\Models\CarType;
use App\Models\Parking;
use App\Models\Partner;
use App\Models\PartnerType;
use App\Models\Pricing;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PartnerController extends AppController
{
    private $parkingAjax = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['role:Partner'])
            ->only([
                'getParkings',
                'parkingList',
                'addParking',
                'removeParking',
                ]);

        $this->middleware(['permission:partner_view'])->only('index', 'show');
        $this->middleware(['permission:partner_create'])->only('create', 'store');
        $this->middleware(['permission:partner_update'])->only('edit', 'update');
//        $this->middleware(['permission:partner_delete'])->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        dd(auth()->user()->id);
        if(auth()->user()->hasRole(['Admin'])){
            $p_ids = auth()->user()->partners->pluck('id');
            $partners = Partner::whereIn('id',$p_ids)->with('partnerType')->orderBy('status', 'DESC')->paginate();
        }
        if(auth()->user()->hasRole(['SuperAdmin'])) {
            $partners = Partner::with('partnerType')->orderBy('status', 'DESC')->paginate();
        }
            $title = __('Partners');

            return view('partners.index', compact('title', 'partners'));
        }

        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $partner_types = PartnerType::all();
        $car_types  = CarType::where('is_active', 1)
            ->select('id','name')
            ->orderBy('rank', 'desc')->orderBy('name', 'ASC')
            ->get();
        $pricings = createPriceList($car_types);
//dd($pricings);
        $title = __('Create new Partner');

        return view('partners.create', compact('title', 'partner_types', 'pricings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd($request->pricings);
        $request->validate([ 'inn' => 'required|unique:partners']) ;

//        $validator = Validator::make(array_merge($request->pricings,['inn'=>$request->inn]))->validate();
//        $validator = Validator::make($request->pricings, [
//            '*.regular_price' => ['nullable', 'integer'],
//            '*.dicount_price' => ['nullable', 'integer'],
//            '*.free_days' => ['nullable', 'integer'],
//            '*.car_type_id' => ['integer'],
////            'inn' => 'required|unique:partners'
//
//        ])->validate();
//        if ($validator->fails()) {
////            dd($validator->errors());
//            return redirect()->back()
//                ->withErrors($validator->errors())
//                ->withInput();
//        }




        $partnerData = [
            'name' => $request->name,
            'shortname' => $request->shortname,
            'inn' => $request->inn,
            'kpp' => $request->kpp,
            'base'=>$request->base,
            'partner_type_id' => $request->partner_type,
            'status' => $request->status,
        ];
        dd($partnerData);
        $partner = Partner::create($partnerData);

        $lv = $partner->pricings()->createMany(
            $request->pricings
        );


//        return ($partner->exists)
//            ? redirect()->route('partners.index')->with('success', __('Saved.'))
//            : redirect()->back()->with('error', __('Error'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $partner = Partner::findOrFail($id);
        $car_types  = CarType::where('is_active', 1)
            ->select('id','name')
            ->orderBy('rank', 'desc')->orderBy('name', 'ASC')
            ->get();

        $pricings = createPriceList($car_types, $partner->pricings);
        $partner_types = PartnerType::all();
        $title = __('Edit partner: :Partner', ['partner' => $partner->name]);
        return view('partners.edit', compact('title', 'partner', 'partner_types', 'pricings'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->pricings, [
            '*.regular_price' => ['nullable', 'integer'],
            '*.dicount_price' => ['nullable', 'integer'],
            '*.free_days' => ['nullable', 'integer'],
            '*.car_type_id' => ['integer'],
        ])->validate();

        $partner = Partner::findOrFail($id);

        $this->validator($request->all())->validate();

        $request->request->add(['partner_type_id' => $request->partner_type]);
        $is_update = $partner->update($request->except(['partner_type']));

//        $is_update = Partner::where('id', $id)->update($request->except(['partner_type', '_token', '_method']));

        foreach ($request->pricings as $pricing) {
            Pricing::updateOrCreate(
                [
                    'car_type_id'=> $pricing['car_type_id'],
                    'partner_id'=> $partner->id,
                ],
                [
                    'free_days'=> $pricing['free_days'],
                    'discount_price'=> $pricing['discount_price'],
                    'regular_price'=> $pricing['regular_price']
                ]
            );
        }

        return ($is_update)
            ? redirect()->route('partners.index')->with('success', __('Saved.'))
            : redirect()->back()->with('error', __('Error'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:100', 'unique:partner_types'],
            'address' => ['string', 'max:100', 'nullable'],
            'phone' => ['numeric', 'min:20', 'nullable'],
            'email' => ['string', 'email', 'nullable'],
            'rank' => ['required', 'numeric', 'min:0'],
            'partner_type' => ['required', 'exists:partner_types,id'],
            'status' => ['boolean'],
        ]);
    }

    public function getParkings(Request $request)
    {
        if(!$request->ajax()) abort(404);

        if($request->has('q')){
            $search = $request->q;
        }
        $this->parkingAjax = DB::table('legal_parking')
            ->join('parkings', 'legal_parking.parking_id', '=', 'parkings.id')
            ->join('legals', 'legal_parking.legal_id', '=', 'legals.id')
            ->select('parkings.id','parkings.title', 'legals.inn')
            ->whereNotIn('parkings.id', DB::table('parking_user')
                ->distinct()
                ->where('parking_user.user_id', auth()->id())
                ->pluck('parking_user.parking_id'))
            ->where(function($query) use ($search){
                $query->where('parkings.title','LIKE',"%{$search}%")
                    ->orWhere('legals.inn','=',"{$search}");
            })
            ->get();

        return response()->json($this->groupInns());
    }

    public function parkingList()
    {
        $title = __('Parking lots');
        $partnerParkings = auth()->user()->partnerParkings;
        return view('partners.parking.index', compact('title', 'partnerParkings'));
    }

    public function addParking(Request $request)
    {

        Validator::make($request->all(), [
            'parking' => ['exists:parkings,id', 'required'],
        ])->validate();

        try {
            DB::beginTransaction();
            auth()->user()->partnerParkings()->detach($request->parking);
            auth()->user()->partnerParkings()->attach($request->parking);
            DB::commit();

            return redirect()->route('partner.parkings')->with('success', __('Saved.'));

        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('Error') . ': ' . __('Failed to save'));
        }
    }

    public function removeParking($id)
    {
        return (auth()->user()->partnerParkings()->detach($id))
            ? redirect()->back()->with('success', __('Removed from your list'))
            : redirect()->back()->with('error', __('Error'));
    }

    private function groupInns()
    {
        if(empty($this->parkingAjax)) return $this->parkingAjax;
        $temps = [];
        $this->parkingAjax->each(function ($item, $key) use (&$temps) {
            if(Arr::exists($temps, $item->id)) {
                $temps[$item->id]->inn .= ', ' . $item->inn;
            } else {
                $temps[$item->id] = $item;
            }

        });

        return array_values(Arr::sort($temps, function($value){
            return $value->title;
        }));
    }
}
