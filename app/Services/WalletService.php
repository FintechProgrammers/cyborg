<?php

namespace App\Services;

use App\Models\Reward;
use App\Models\Wallet;

class WalletService
{
    function updateBalance($userId, $amount)
    {
        $wallet = Wallet::where('user_id', $userId)->first();

        if (!empty($wallet)) {
            $wallet->update([
                'balance'  => (int)$wallet->balance + (int)$amount
            ]);
        } else {
            Wallet::create([
                'user_id' => $userId,
                'balance'  => (int)$amount
            ]);
        }
    }


    function recordReward($userId, $childId, $amount)
    {
        Reward::create([
            'user_id'       => $userId,
            'invit'         => $childId,
            'amount'        => $amount,
            'type'          => 'direct',
            'description'   => "TRADING FEE EARN"
        ]);
    }
}
