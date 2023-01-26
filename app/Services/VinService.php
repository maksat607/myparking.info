<?php

namespace App\Services;

use Sunrise\Vin\Vin;
use VinDecode\VinDecode;
use Errorname\VINDecoder\Decoder;
class VinService
{
    public function vin()
    {
//        $balance = Decoder::balance();
//        $vin = Decoder::decode('XXXDEF1GH23456789');
//        dd($vin->available());
//        $vin_decode = new VinDecode();
//
//        $vin_decode->setVIN('wba78dy0608c20067');
//        $vin_decode->searchVIN();
//        dump($vin_decode);
        $vin = new Vin('Z8PFF3A5XCA022806');
        dd($vin->toArray());
    }
}
