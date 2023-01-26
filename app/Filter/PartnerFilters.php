<?php


namespace App\Filter;


class PartnerFilters extends QueryFilter
{
    public function user()
    {
        return $this->builder->where('base_type', 'user');
    }

    public function public()
    {
        return $this->builder->where('base_type', 'public');
    }

    public function search($keyword)
    {
        return $this->builder
            ->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('shortname', 'like', '%' . $keyword . '%')
                    ->orWhere('inn', 'like', '%' . $keyword . '%')
                    ->orWhere('kpp', 'like', '%' . $keyword . '%');
            })
            ->orwhereHas('partnerType', function ($query) use ($keyword) {
                return $query->where('name', 'like', '%' . $keyword . '%');
            })
            ;
    }

}
