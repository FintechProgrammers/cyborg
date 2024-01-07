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
        $validator = Validator::make($request->all(), [
            'asset'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError("Validation error", $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {

            $user = $request->user;

            // Check if user already have a deposit addess
            $wallet = Wallet::where('user_id', $user->id)->first();

            if (!$wallet) {

                $coinpay = new \App\Services\Gateways\Coinpay();

                $response = $coinpay->getDepositAddress($request->asset);

                if ($response['error'] !== 'ok') {
                    return $this->sendError("Unable to create deposit address at the moment.", [], Response::HTTP_SERVICE_UNAVAILABLE);
                }

                $address = $response['result']['address'];

                Wallet::create([
                    'user'    => $user->id,
                    'address' => $address,
                ]);
            } else {
                $address = $wallet->address;
            }

            return $this->sendResponse(['address' => $address], "Deposit address", Response::HTTP_CREATED);
        } catch (\Exception $e) {
            logger(["deposit" => $e->getMessage()]);

            return $this->sendError("Unable to create deposit address at the moment.", [], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }
}
