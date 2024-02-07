<?php

namespace App\Console\Commands;

use App\Models\Reward;
use App\Models\Wallet;
use Illuminate\Console\Command;

class SettleReward extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settle:reward';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rewards = Reward::where('is_settled', false)->get();

        foreach ($rewards as $reward) {
            // get user wallet
            $wallet = Wallet::where('user_id', $reward->user_id)->first();

            if (!empty($wallet)) {
                $wallet->update([
                    'balance' => (float)$wallet->balance + (float)$reward->amount
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
    }
}
