<?php


namespace App\View\Composers;


use App\Models\Parking;
use App\Models\Partner;
use App\Models\User;
use Illuminate\View\View;

class ApplicationFilterComposer
{
    protected $partners;
    protected $parkings;
    protected $user;

    public function __construct(Partner $partners, Parking $parkings, User $user)
    {
//        dd($parkings->parkings());
        if(auth()->user()->hasRole(['Partner'])){
            $partners = collect([auth()->user()->partner]);
        }
        if(auth()->user()->hasRole(['Admin'])){
            $partners = auth()->user()->partners;
        }
        if(auth()->user()->hasRole(['SuperAdmin'])){
            $partners = Partner::all();
        }
        if(!auth()->user()->hasRole(['SuperAdmin|Admin|Partner'])){
            $partners = auth()->user()->owner->partners;
        }
//        $this->partners = $partners->partners()->orderBy('name', 'ASC')->get();
        $this->partners = $partners->sortBy('name');
        $this->parkings = $parkings->parkings()->orderBy('title', 'ASC')->get();
        $this->user = $user->usersFilter();
    }

    public function compose(View $view)
    {
        $view
            ->with('partners', $this->partners)
            ->with('parkings', $this->parkings)
            ->with('user', $this->user);
    }
}
