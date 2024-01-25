<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class WithdrawalController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount'    => ['required', 'numeric'],
            'address'   => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->sendError("Validation error", $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $minApprovalLimit = systemSettings()->minimum_widthdrawal;
        $maxApprovalLimit = systemSettings()->maximum_widthdrawal;

        if ($request->amount < $minApprovalLimit || $request->amount > $maxApprovalLimit) {
            return $this->sendError("Amount must be between $minApprovalLimit and $maxApprovalLimit for approval");
        }

        try {

            // Continue with the rest of your logic if the validation passes
            $user = $request->user;

            $coin = strtoupper('usdt.trc20');

            $fee = ($request->amount / 100) * systemSettings()->withdrawal_fee;

            $payload = [
                'amount'  => $request->amount,
                'address' => $request->address,
            ];

            $amount = $request->amount + $fee;

            // Check if user already have a deposit addess
            $wallet = Wallet::where('user_id', $user->id)->first();

            if (!$wallet) {
                return $this->sendError("Insufficient funds.", [], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            if ($amount > $wallet->balance) {
                return $this->sendError("Insufficient funds.", [], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $wallet->update([
                'balance'  => $wallet->balance - $request->amount,
            ]);

            $transaction = [
                'user_id'           => $wallet->user_id,
                'coin'              => 'USDT',
                'amount'            => $request->amount,
                'fee'               => $fee,
                'type'              => 'debit',
                'action'            => 'withdrawal',
                'status'            => 'pending',
                'address'           => $request->address,
                'request_payload'   => json_encode($payload),
                'narration'         => "Withdraw {$request->amount} USDT to {$request->address}"
            ];

            if (strtolower(systemSettings()->automatic_withdrawal) == 'enable') {
                $coinpay = new \App\Services\Gateways\Coinpay();

                // check coinpayment balance
                $balances = $coinpay->getBalance();

                if ($balances['result'][$coin]['balancef'] < $request->amount) {
                    sendToLog("Coinpayment insufficient balance");
                    return $this->sendError("Unable to complete your request at the moment.", [], Response::HTTP_SERVICE_UNAVAILABLE);
                }

                $response = $coinpay->withdrawal($payload);

                if (empty($response)) {
                    sendToLog(["withdrawal response" => $response]);
                    return $this->sendError("Unable to complete your request at the moment.", [], Response::HTTP_SERVICE_UNAVAILABLE);
                }

                if ($response['error'] !== 'ok') {
                    sendToLog(["withdrawal response" => $response]);
                    return $this->sendError("Unable to complete your request at the moment.", [], Response::HTTP_SERVICE_UNAVAILABLE);
                }

                $transaction['reference'] = $response['result']['id'];
            } else {
                $transaction['reference'] = generateReference();
                $transaction['is_manual'] = true;
            }

            Transaction::create($transaction);

            return $this->sendResponse([], "Withdrawal request place successfully.", Response::HTTP_CREATED);
        } catch (\Exception $e) {
            sendToLog(["withdrawal" => $e]);

            return $this->sendError("Unable to complete your request at the moment.", [], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }
}
