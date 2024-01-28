<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserExchange;
use Illuminate\Console\Command;
use Spatie\Async\Pool;

class GetExchange extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:balace';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'et Balance of binded exchange.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bindedExchanges = UserExchange::with('exchange')->where('is_binded', true)->get();

        // $pool = Pool::create();

        foreach ($bindedExchanges as $binded) {
            // $pool->add(function () use ($binded) {
            $setupData = [
                'apikey'        => $binded->api_key,
                'secret'        => $binded->api_secret,
                'password'      => $binded->api_passphrase,
                'trade_type'    => "spot",
            ];

            // connect to exchange
            $exchangeKey = ucfirst($binded->exchange->slug);
            $exchangeService = "\\App\\Services\\Exchange\\{$exchangeKey}";

            $exchangeService = new $exchangeService($setupData);

            $spotBalance = $exchangeService->getBalance();

            $futuresBalance = 0.00; // Default value if futures not enabled

            if ($binded->exchange->futures) {
                $setupData['trade_type'] = "future";
                $exchangeFuturesService = new $exchangeService($setupData);
                $futuresBalance = $exchangeFuturesService->getBalance()['total'] ?? 0.00;
            }

            // Check if there are changes before updating
            if ($binded->spot_balance != $spotBalance['total'] || $binded->future_balance != $futuresBalance) {
                $binded->update([
                    'spot_balance'      => !empty($spotBalance['total']) ? $spotBalance['total'] : 0.00,
                    'future_balance'    => $futuresBalance,
                    'is_binded'         => true,
                ]);
            }
            // });
        }

        // $pool->wait();
    }
}
