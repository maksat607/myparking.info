<?php


namespace App\Filter;


class ApplicationFilters extends QueryFilter
{
    public function partner($id)
    {
        return $this->builder->where('partner_id', $id);
    }

    public function parking($id)
    {
        return $this->builder->where('parking_id', $id);
    }

    public function user($id)
    {
        return $this->builder->where('accepted_by', $id);
    }

    public function search($keyword)
    {
        return $this->builder
            ->where('car_title', 'like', '%'.$keyword.'%')
            ->orWhere('vin', 'like', '%'.$keyword.'%')
            ->orWhere('license_plate', 'like', '%'.$keyword.'%')
            ;
    }

    public function favorite()
    {
        return $this->builder->where('favorite', true);
    }
}
