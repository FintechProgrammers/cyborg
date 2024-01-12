<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransferRequest;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\WalletResource;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    function index(Request $request)
    {
        // Get the value of user_id from the URL parameters
        $user = $request->user;

        $wallet = Wallet::where('user_id', $user->id)->first();

        if (!$wallet) {
            $wallet = Wallet::create(['user_id' => $user->id]);
        }

        $wallet = new WalletResource($wallet);

        return $this->sendResponse($wallet, "User wallet", 200);
    }

    public function transfer(TransferRequest $request)
    {
        $user = request()->user;

        $wallet = Wallet::where('user_id', $user->id)->first();

        if (!$wallet) {
            return $this->sendError("Insufficient balance to transfer.", [], 422);
        }

        if ($request->amount > $wallet->balance) {
            return $this->sendError("Insufficient balance to transfer.", [], 422);
        }

        $wallet->update([
            'balance'   => $wallet->balance - $request->amount,
            'fee'       => $wallet->fee + $request->amount,
        ]);

        $transaction = Transaction::create([
            'user_id'       => $user->id,
            'reference'     => generateReference(),
            'coin'          => 'USDT',
            'amount'        => $request->amount,
            'type'          => 'debit',
            'action'        => 'transfer',
            'status'        => 'complete',
            'narration'     => 'Transfer to fee wallet.'
        ]);

        $wallet = new WalletResource($wallet->refresh());

        return $this->sendResponse($wallet, "Transfer to trading fee was successfull.", 200);
    }

    function transactions(Request $request)
    {
        $user = $request->user;

        $transactions = Transaction::where('user_id', $user->id)->get();

        $transaction = TransactionResource::collection($transactions);

        return $this->sendResponse($transaction,"Transactions", 200);
    }
}
