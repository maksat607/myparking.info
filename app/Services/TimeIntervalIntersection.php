<?php

namespace App\Services;

use Spatie\Period\Period;

class TimeIntervalIntersection
{


    public static function getDays($periodA, $periodB)
    {
        $periods = app(TimeIntervalIntersection::class);
        return $periods->calculatePeriodsOverlap($periodA, $periodB);
    }

    public function calculatePeriodsOverlap($periodA, $periodB)
    {
        if (request()->get('status_id') == 'instorage' || count(request()->all()) == 0) {
            return $periodA[0]->diff($periodA[1])->days + 1;
        }
        $a = Period::make($periodA[0]->format('Y-m-d'), $periodA[1]->format('Y-m-d'));
        $b = Period::make($periodB[0]->format('Y-m-d'), $periodB[1]->format('Y-m-d'));

        $times = $a->overlap($b);
        if ($times == null) {
            return 0;
        }
        $start = $times->includedStart();
        $end = $times->includedEnd();
        return $start->diff($end)->days + 1;
    }


}

