<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CoinpaymentController extends Controller
{
    public function __invoke(Request $request)
    {

        // sendToLog(["Coinpay webhook Log" => file_get_contents('php://input')]);

        try {

            // Validated marchante id
            $cp_merchant_id = config('constants.coinpay.marchant_id'); //defined in pure_config
            $cp_ipn_secret = config('constants.coinpay.private_key'); //defined in pure_config

            $hmac = hash_hmac("sha512", file_get_contents('php://input'), trim($cp_ipn_secret));

            if (empty($request->input('merchant')) || $request->input('merchant') != trim($cp_merchant_id)) {
                sendToLog(["Coinpay Webhook Log" => "No or incorrect Merchant ID passed"]);
                return response('Successful', Response::HTTP_OK)->header('Content-Type', 'text/plain');
            }

            // if (!hash_equals($hmac, $request->header('Hmac')))
            // {
            //     sendToLog(["Coinpay Webhook Log" => $hmac . 'HMAC signature does not match ' . $request->header('Hmac')]);

            //     return response('Successful', Response::HTTP_OK)->header('Content-Type', 'text/plain');
            // }

            // / You can extract data from the request like this:
            $ipn_type = $request->input('ipn_type');
            $status = $request->input('status');

            if ($status == 100 || $status == 2) {
                // check if transaction is a deposit
                if ($ipn_type === "deposit") {
                    self::deposit($request);
                } else if ($ipn_type === "withdrawal") {
                    self::withdrawal($request);
                }
            }

            return response('Successful', Response::HTTP_OK)->header('Content-Type', 'text/plain');
        } catch (\Exception $e) {
            sendToLog(["Coinpay webhook Log" => $e->getMessage()]);

            return response('Successful', Response::HTTP_OK)->header('Content-Type', 'text/plain');
        }
    }

    function deposit($request)
    {
        $address = $request->input('address');
        $amount = $request->input('amount');
        $txn_id = $request->input('deposit_id');
        $currency = $request->input('currency');
        // $fee = $request->input('fee');
        $fee = 0;

        // check if transactions already exist
        $transactionExist =  Transaction::where('reference', $txn_id)->first();

        if (!$transactionExist) {
            $amount = $request->amount - $fee;
            // Get wallet
            $wallet = Wallet::where('address', $address)->first();

            if ($wallet) {
                $wallet->update([
                    'balance' => $wallet->balance + $amount
                ]);

                Transaction::create([
                    'user_id'       => $wallet->user_id,
                    'reference'     => $txn_id,
                    'coin'          => 'USDT',
                    'amount'        => $amount,
                    'type'          => 'credit',
                    'action'        => 'deposit',
                    'status'        => 'complete',
                    'fee'           => $fee,
                    'narration'     => $request->status_text
                ]);

                // $pushToken = $wallet->user->fcm_token;

                // $fcmTokens = [$pushToken];

                // $data = [
                //     'push_tokens' => $fcmTokens,
                //     'title' => "Deposit",
                //     'message' => "Your deposit of {$amount} USDT was successfully.",
                // ];

                // dispatch(new \App\Jobs\PushNotificationJob($data));
            }
        }
    }

    function withdrawal($request)
    {
        $txn_id = $request->input('id');

        // get transaction
        $transaction =  Transaction::where('reference', $txn_id)->where('status', 'pending')->where('action', 'withdrawal')->first();

        if ($transaction) {
            $transaction->update([
                'status'        => 'complete',
            ]);

            // $pushToken = $transaction->user->fcm_token;

            // $fcmTokens = [$pushToken];

            // $data = [
            //     'push_tokens' => $fcmTokens,
            //     'title' => "Deposit",
            //     'message' => "Your withdrawal of {$transaction->amount} USDT was successfully.",
            // ];

            // dispatch(new \App\Jobs\PushNotificationJob($data));
        }
    }
}
