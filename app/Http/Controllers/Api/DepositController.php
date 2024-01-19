<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class DepositController extends Controller
{
    public function __invoke(Request $request)
    {
        try {

            $user = $request->user;

            // Check if user already have a deposit addess
            $wallet = Wallet::where('user_id', $user->id)->first();

            if (!$wallet || empty($wallet->address)) {

                $coinpay = new \App\Services\Gateways\Coinpay();

                $response = $coinpay->getDepositAddress();

                if ($response['error'] !== 'ok') {
                    return $this->sendError("Unable to create deposit address at the moment.", [], Response::HTTP_SERVICE_UNAVAILABLE);
                }

                $address = $response['result']['address'];
            } else {
                $address = $wallet->address;
            }

            Wallet::updateOrCreate([
                'user_id'    => $user->id,
            ], [
                'address' => $address,
            ]);

            return $this->sendResponse(['address' => $address], "Deposit address", Response::HTTP_CREATED);
        } catch (\Exception $e) {
            logger(["deposit" => $e->getMessage()]);

            return $this->sendError("Unable to create deposit address at the moment.", [], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }
}
