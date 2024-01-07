<?php


namespace App\Services;

use App\Services\Gateways\Coinpay;
use Illuminate\Http\Request;

class DepositService
{

    function getAddress(Request $request)
    {
        $gateway = new Coinpay();

        return $gateway->getDepositAddress($request->currency);
    }
}
