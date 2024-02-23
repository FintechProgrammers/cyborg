<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use App\Models\Wallet;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    function settleReward()
    {
        $rewards = Reward::where('is_settled', false)
        // ->where('user_id', '17409')
            ->get();

        // $rewards->toQuery()->update([
        //     'is_settled' => false
        // ]);

        // dd($rewards->toQuery()->sum('amount'));

        foreach ($rewards as $reward) {
            // get user wallet
            $wallet = Wallet::where('user_id', $reward->user_id)->first();

            if (!empty($wallet)) {
                $wallet->update([
                    'balance' => $wallet->balance + $reward->amount
                ]);
            } else {
                Wallet::create([
                    'user_id'  => $reward->user_id,
                    'balance'  => $reward->amount
                ]);
            }

            $reward->update([
                'is_settled' => true
            ]);
        }

        return response('Successful', 200)->header('Content-Type', 'text/plain');
    }
}
