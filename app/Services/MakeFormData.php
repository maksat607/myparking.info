<?php

namespace App\Services;

class MakeFormData
{
    public function applicationNestedArray($request)
    {
        $arr = '';
        foreach ($request as $key => $items) {
            foreach ($items as $k => $item) {
                $arr = $arr . $key . '[' . $k . '] : ' . $item . PHP_EOL;
            }
        }
        dd($arr);
    }

    public function translateArray($request)
    {
        $arr = '';
        foreach ($request as $key => $items) {
            $arr = $arr . $key . ' : ' . $items . PHP_EOL;

        }
        dd($arr);
    }
}
