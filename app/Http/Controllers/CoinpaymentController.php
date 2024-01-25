<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class CoinpaymentController extends Controller
{
    public function __invoke(Request $req)
    {

        $cp_merchant_id   = config('constants.coinpay.marchant_id');
        $cp_ipn_secret    = config('constants.coinpay.private_key');
        $cp_debug_email   = "";

        /* Filtering */
        if (!empty($req->merchant) && $req->merchant != trim($cp_merchant_id)) {
            if (!empty($cp_debug_email)) {
                logger("No or incorrect Merchant ID passed");
            }
            return response('No or incorrect Merchant ID passed', 401);
        }

        $request = $req->getContent();

        if ($request === FALSE || empty($request)) {
            if (!empty($cp_debug_email)) {
                logger('Error reading POST data');
            }
            return response('Error reading POST data', 401);
        }

        $hmac = hash_hmac("sha512", $request, trim($cp_ipn_secret));
        if (!hash_equals($hmac, $req->server('HTTP_HMAC'))) {
            if (!empty($cp_debug_email)) {
                logger('HMAC signature does not match');
            }
            return response('HMAC signature does not match', 401);
        }

        $transactions = Transaction::where('reference', $req->txn_id)->first();

        if ($transactions) {

            $info = $this->api_call('get_tx_info', ['txid' => $req->txn_id]);

            if ($info['error'] != 'ok') {
                logger(date('Y-m-d H:i:s ') . $info['error']);
            }

            try {
                $transactions->update($info['result']);
            } catch (\Exception $e) {
                logger(date('Y-m-d H:i:s ') . $e->getMessage());
            }
        } else {
            logger('Txn ID ' . $req->txn_id . ' not found from database ?');
        }
    }
}
