<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class WithdrawalController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currency'  => 'required|string',
            'amount'    => 'required|numeric',
            'address'   => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError("Validation error", $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {

            $user = $request->user;

            // Check if user already have a deposit addess
            $wallet = Wallet::where('user_id', $user->id)->first();

            if (!$wallet) {
                return $this->sendError("Insufficient funds.", [], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            if ($request->amount > $wallet->balance) {
                return $this->sendError("Insufficient funds.", [], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $coinpay = new \App\Services\Gateways\Coinpay();

            $data = [
                'amount'        => $request->amount,
                'currency'      => $request->currency,
                'currency2'     => 'USDT'
            ];

            $response = $coinpay->withdrawal($data);

            if (empty($response)) {
                return $this->sendError("Unable to complete your request at the moment.", [], Response::HTTP_SERVICE_UNAVAILABLE);
            }

            if ($response['error'] !== 'ok') {
                return $this->sendError("Unable to complete your request at the moment.", [], Response::HTTP_SERVICE_UNAVAILABLE);
            }

            $wallet->update([
                'balance'  => $wallet->balance - $request->amount,
            ]);

            return $this->sendResponse([], "Withdrawal request place successfully.", Response::HTTP_CREATED);
        } catch (\Exception $e) {
            logger(["deposit" => $e->getMessage()]);

            return $this->sendError("Unable to complete your request at the moment.", [], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }
}
