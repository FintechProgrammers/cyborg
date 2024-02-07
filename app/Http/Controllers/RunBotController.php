<?php

namespace App\Http\Controllers;

use App\Jobs\RunBotJob;
use App\Models\Bot;
use App\Models\ProfitRecord;
use App\Models\TradeHistory;
use App\Models\User;
use App\Models\Wallet;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class RunBotController extends Controller
{
    public function index()
    {
        $bots = Bot::select('uuid')->where('started', true)->where('running', false)
            // ->where('updated_at', '<', now()->subMinutes(1))
            ->get();

        $client = new Client();
        $promises = [];

        foreach ($bots as $bot) {
            $bot->update(['running' => true]);

            // Omit the port number if you are using the default HTTP port (80)
            $promises[] = $client->getAsync('http://104.248.100.252/run/bot', ['query' => ['bot_id' => $bot->uuid]]);
        }

        // Wait for all requests to complete
        Promise\Utils::all($promises)->wait();

        return response('Successful', 200)->header('Content-Type', 'text/plain');
    }

    public function runBot(Request $request)
    {

        $bot = Bot::with(['exchange', 'market', 'user'])->whereUuid($request->query('bot_id'))->first();

        if (!$bot) {
            $botid = $request->query('bot_id');
            sendToLog("bot {$botid} not found");
            return response('Successful', 200)->header('Content-Type', 'text/plain');
        }

        $gasFee = systemSettings()->trade_fee;

        $wallerService = new WalletService();

        $settings = (object)  json_decode($bot->settings, true);

        $trade_values =  (object) json_decode($bot->trade_values, true);

        $market = $bot->market->coin;

        $wallet =  Wallet::where('user_id', $bot->user_id)->first();

        $user = $bot->user;

        if (!empty($wallet)) {

            // check gas fee
            if ($wallet->fee > $gasFee) {

                // connect to exchange
                $exchangeKey = ucfirst($bot->exchange->slug);

                $exchangeService = "\\App\\Services\\Exchange\\{$exchangeKey}";

                $userExchange = \App\Models\UserExchange::where('user_id', $bot->user_id)->where('exchange_id', $bot->exchange_id)->where('is_binded', true)->first();

                if ($userExchange) {

                    // Spot Trading
                    if ($bot->trade_type === "spot") {
                        $this->spotTrade($userExchange, $exchangeService, $trade_values, $market, $settings, $bot, $wallerService, $user, $gasFee, $wallet);
                    }

                    // Futures Trading
                    if ($bot->trade_type === "future") {
                        $this->futuresTrade($userExchange, $exchangeService, $trade_values, $market, $settings, $bot, $wallerService, $user, $gasFee, $wallet);
                    }
                } else {
                    $bot->update([
                        'started' => false,
                        'running' => false,
                        'logs'     => "{$bot->exchange->name} exchange is not binded",
                    ]);
                }
            } else {
                $bot->update([
                    'started' => true,
                    'running' => false,
                    'logs'     => "Your are low on gas fee. you need upto {$gasFee} USDT as gas fee. ",
                ]);
            }
        } else {
            $bot->update([
                'started' => true,
                'running' => false,
                'logs'     => "Your are low on gas fee. you need upto {$gasFee} USDT as gas fee. ",
            ]);
        }

        return response('Successful', 200)->header('Content-Type', 'text/plain');
    }

    function spotTrade($userExchange, $exchangeService, $trade_values, $market, $settings, $bot, $wallerService, $user, $gasFee, $wallet)
    {
        try {
            $apiData = [
                'apikey'        => $userExchange->api_key,
                'secret'        => $userExchange->api_secret,
                'password'      => $userExchange->api_passphrase,
                'trade_type'    =>  $bot->trade_type,
                'market'        =>  $market
            ];

            $exchange = new $exchangeService($apiData);

            // first entry
            if (!$trade_values->in_position && !$trade_values->buy_position && $trade_values->margin_calls === 0) {

                $trade_price = (float) $exchange->fetchTicker();
                $quantity = (float) $settings->first_buy / (float) $trade_price;
                $order = $exchange->createMarketBuyOrder($quantity);

                $quantity = $order['quantity'];
                $position_amount = $order['position_amount'];
                $trade_price = $order['trade_price'];
                $orderId = $order['order_id'];

                $cal_qty = $quantity * 0.1 / 100;
                $quantity = $quantity - $cal_qty;

                $in_position = true;
                $buy_position = true;
                $sell_position = false;
                $margin_calls = 0;
                $floatingLoss = 0;
                $profit = 0;

                $tradeValues = tradeValues($position_amount, $in_position, $buy_position, $sell_position, $margin_calls, $floatingLoss, $trade_price, $quantity, $profit, $trade_price, $trade_price);

                $bot->update([
                    'running'           => false,
                    'trade_values'      => json_encode($tradeValues),
                    'logs'              => "First buy order succesfully filled."
                ]);

                $this->recordTrade($bot, $trade_price, $quantity, "buy", 0, false);

                return;
            }

            // take profit
            if ($trade_values->in_position && $trade_values->buy_position) {
                $cal = (float)$trade_values->trade_price * (float)$settings->take_profit / 100;
                $cal2 = (float)$trade_values->trade_price + $cal;
                $trade_price = (float)$exchange->fetchTicker();

                if ($trade_price > $cal2) {
                    $nqty = (float)$trade_values->quantity * 0.1 / 100;
                    $quantity = (float)$trade_values->quantity - $nqty;
                    $quantity = number_format($quantity, 5);
                    $sold = 1;
                    $order = $exchange->createMarketSellOrder($quantity);

                    $quantity = $order['quantity'];
                    $position_amount = $order['position_amount'];
                    $trade_price = $order['trade_price'];
                    $orderId = $order['order_id'];

                    // if ($position_amount > 0) {
                    $profit = $position_amount - $trade_values->position_amount;

                    $this->takeProfit($profit, $bot, $trade_price, $quantity, $gasFee, $wallerService, $wallet, $user, "sell");
                    // }

                    return;
                }
            }

            // martingale
            $margin_limit = (int) $settings->margin_limit;
            $margin_call  = (int)$trade_values->margin_calls;

            $price_drop = explode("|", $settings->price_drop);
            $m_ration = explode("|", $settings->m_ratio);

            if ($trade_values->in_position && $trade_values->buy_position && $margin_limit > 0 && $margin_limit !=  $margin_call) {
                $cal = (float) $trade_values->first_price * (float)$price_drop[$margin_call] / 100;
                $cal2 = (float) $trade_values->first_price - $cal;

                $trade_price = (float) $exchange->fetchTicker();

                if ($trade_price < $cal2) {
                    $qty = (float) $settings->first_buy * (float) $m_ration[$margin_call] / $trade_price;
                    $qty = number_format($qty, 5);
                    $order = $exchange->createMarketBuyOrder($qty);

                    $quantity = $order['quantity'];
                    $position_amount = $order['position_amount'];
                    $trade_price = $order['trade_price'];
                    $orderId = $order['order_id'];

                    $cal_qty = (float)$qty * 0.1 / 100;
                    $quantity = $quantity - $cal_qty;
                    $position_amount = $position_amount + (float) $trade_values->position_amount;
                    $total_quantity = $qty + (float) $trade_values->quantity;

                    $average_price = $position_amount / $total_quantity;

                    $trade_price = $average_price;

                    $margin_call = $margin_call + 1;

                    $position_amount = $position_amount;
                    $in_position = true;
                    $buy_position = true;
                    $sell_position = false;
                    $margin_call = $margin_call;
                    $floatingLoss = 0;
                    $trade_price = $trade_price;
                    $quantity = $quantity;
                    $profit = $trade_values->profit;
                    $firstPrice = $trade_values->first_price;

                    $tradeValues = tradeValues(
                        $position_amount,
                        $in_position,
                        $buy_position,
                        $sell_position,
                        $margin_call,
                        $floatingLoss,
                        $trade_price,
                        $quantity,
                        $profit,
                        $firstPrice,
                        $average_price
                    );

                    $bot->update([
                        'running'           => false,
                        'trade_values'      => json_encode($tradeValues),
                        'logs'              => "No {$margin_call} martingale buy succesfully filled."
                    ]);

                    // record trade history
                    $this->recordTrade($bot, $trade_price, $quantity, "buy", $trade_values->profit, false);

                    return;
                }
            }
        } catch (\Exception $e) {

            sendToLog($e);

            $responseString = $e->getMessage();

            if ($e instanceof \ccxt\InsufficientFunds) {
                // Find the position of the first curly brace
                $bracePosition = strpos($responseString, '{');

                if ($bracePosition !== false) {
                    // Extract the JSON string
                    $jsonString = substr($responseString, $bracePosition);

                    // Decode the JSON string
                    $responseArray = json_decode($jsonString, true);

                    if ($responseArray !== null && isset($responseArray['msg'])) {
                        $errorMessage = $responseArray['msg'];
                        // Now $errorMessage contains the value of "msg"
                        $responseString = $errorMessage;
                    } else if ($responseArray !== null && isset($responseArray['retMsg'])) {
                        $errorMessage = $responseArray['retMsg'];
                        // Now $errorMessage contains the value of "msg"
                        $responseString = $errorMessage;
                    } else {
                        logger($e->getMessage());
                    }
                } else {
                    logger($e->getMessage());
                }

                // Handle InsufficientFunds exception
                $bot->update([
                    'started' => false,
                    'running' => false,
                    'logs'     => $responseString,
                ]);
            }
        }
    }

    function futuresTrade($userExchange, $exchangeService, $trade_values, $market, $settings, $bot, $wallerService, $user, $gasFee, $wallet, $leverage = 1)
    {
        try {

            $apiData = [
                'apikey'        => $userExchange->api_key,
                'secret'        => $userExchange->api_secret,
                'password'      => $userExchange->api_passphrase,
                'trade_type'    =>  $bot->trade_type,
                'market'        =>  $market
            ];

            $exchange = new $exchangeService($apiData);

            $balance = $exchange->getBalance();
            $position_amount = $trade_values->position_amount;
            $in_position = $trade_values->in_position;
            $trade_price = $trade_values->trade_price;
            $quantity = $trade_values->quantity;
            $first_price =  $trade_values->first_price;
            $floating_loss = $trade_values->floating_loss;
            $current_profit = $trade_values->profit;
            $margin_calls = $trade_values->margin_calls;
            $leverage = 1;

            $check_balance = $settings->capital - 5;

            // calculate percentage of exchange balance
            $balancePercentage = ($check_balance * 50) / 100;

            // First entry
            if (!$in_position && $margin_calls === 0) {

                if ($balance['free'] > $balancePercentage) {
                    if ($settings->capital > 1) {

                        $trade_price = (float) $exchange->fetchTicker();

                        $quantity = (float) $settings->first_buy / (float) $trade_price;
                        $quantity = (float) $quantity * (float) $leverage;

                        // if kucoin
                        if ($bot->exchange->slug == "kucoin") {
                            $lot =(float) $exchange->fetchMarkets();

                            $quantity = $quantity / $lot;
                        }

                        $options = [
                            "leverage" => $leverage,
                            "newClientOrderId" => "x-zcYWaQcS",
                            "reduceOnly" => false,
                        ];

                        //
                        if ($bot->strategy_mode === "long" && $balance['free'] > $settings->first_buy) {
                            $order = $exchange->createMarketBuyOrder($quantity, $options);
                        } else if ($bot->strategy_mode === "short" && $balance['free'] > $settings->first_buy) {
                            $order = $exchange->createMarketSellOrder($quantity, $options);
                        }

                        // Sleep for 3 seconds
                        sleep(3);

                        if (!empty($order)) {
                            $quantity = $order['quantity'];
                            $position_amount = $order['position_amount'];
                            $trade_price = $order['trade_price'];
                            $orderId = $order['order_id'];
                            $averagePrice = $order['trade_price'];

                            $position_amount = $position_amount;
                            $in_position = true;
                            $buy_position = false;
                            $sell_position = false;
                            $margin_call = 0;
                            $floatingLoss = 0;
                            $trade_price = $trade_price;
                            $quantity = $quantity;
                            $profit = 0;
                            $firstPrice = $trade_price;
                            $averagePrice = $averagePrice;

                            $tradeValues = tradeValues(
                                $position_amount,
                                $in_position,
                                $buy_position,
                                $sell_position,
                                $margin_call,
                                $floatingLoss,
                                $trade_price,
                                $quantity,
                                $profit,
                                $firstPrice,
                                $averagePrice
                            );

                            $bot->update([
                                'running'           => false,
                                'trade_values'      => json_encode($tradeValues),
                                'logs'              => "First buy order successfully filled."
                            ]);

                            // record trade history
                            if ($bot->strategy_mode === "long") {
                                $this->recordTrade($bot, $trade_price, $quantity, "buy", 0, false);
                            } else if ($bot->strategy_mode === "short") {
                                $this->recordTrade($bot, $trade_price, $quantity, "sell", 0, false);
                            }
                        } else {
                            $bot->update([
                                'running'   => false,
                            ]);
                        }
                    } else {
                        $bot->update([
                            'started'   => false,
                            'running'   => false,
                            'logs'      => 'Your bot does not have total capital setting. Enter capital in settings to proceed with trading.'
                        ]);
                    }
                } else {
                    $bot->update([
                        'started'   => true,
                        'running'   => false,
                        'logs'      => 'Your exchange balance is below your trade capital.'
                    ]);
                }

                return;
            }

            //
            if ($in_position) {

                $trade_price = (float) $exchange->fetchTicker();

                $positions = $exchange->getPositions();

                $avg_price = $positions['average_price'];
                $position_amount = $positions['position_amount'];
                $quantity = $positions['quantity'];
                $current_profit = $positions['current_profit'];
                $floating_loss = $positions['floating_loss'];
                $side = strtolower($positions['side']);

                // $rate = $exchange->lastResponseHeaders();

                // if ($rate >= 1000) {

                //     $tradeValues = [
                //         'quantity'          => $quantity,
                //         'trade_price'       => 0,
                //         'first_price'       => 0,
                //         'position_amount'   => $position_amount,
                //         'margin_calls'      => 0,
                //         'in_position'       => true,
                //         'buy_position'      => false,
                //         'sell_position'     => false,
                //         'profit'            => 0,
                //         'floating_loss'     => 0,
                //     ];

                //     $bot->update([
                //         'running'           => false,
                //         'trade_values'      => json_encode($tradeValues),
                //         'logs'              => "Rate limit reached"
                //     ]);

                //     return;
                // }

                if ($position_amount === 'None' || $position_amount < 1) {

                    $position_amount = 0;
                    $in_position = false;
                    $buy_position = false;
                    $sell_position = false;
                    $margin_call = 0;
                    $floatingLoss = $floating_loss;
                    $trade_price = 0;
                    $quantity = 0;
                    $profit = $current_profit;
                    $firstPrice = 0;

                    $tradeValues = tradeValues(
                        $position_amount,
                        $in_position,
                        $buy_position,
                        $sell_position,
                        $margin_call,
                        $floatingLoss,
                        $trade_price,
                        $quantity,
                        $profit,
                        $firstPrice
                    );

                    $bot->update([
                        'running'           => false,
                        'trade_values'      => json_encode($tradeValues),
                        'logs'              => "Rate limit reached"
                    ]);

                    return;
                }

                // capture all data and save again
                $position_amount = $position_amount;
                $in_position = $in_position;
                $buy_position = false;
                $sell_position = false;
                $margin_call = $margin_calls;
                $floatingLoss = $floating_loss;
                $trade_price = $trade_price;
                $quantity = $quantity;
                $profit = $current_profit;
                $firstPrice = $first_price;
                $averagePrice = $avg_price;

                $tradeValues = tradeValues(
                    $position_amount,
                    $in_position,
                    $buy_position,
                    $sell_position,
                    $margin_call,
                    $floatingLoss,
                    $trade_price,
                    $quantity,
                    $profit,
                    $firstPrice,
                    $averagePrice
                );

                $bot->update([
                    'running'           => false,
                    'trade_values'      => json_encode($tradeValues),
                ]);

                // Take profit
                # how much profit to expect.
                $expectprofit = $position_amount * $settings->take_profit / 100;

                $trade_price = (float) $exchange->fetchTicker();

                if ($current_profit >= $expectprofit) {
                    // Take profit long
                    if ($bot->strategy_mode === "long") {
                        if ($side == "long") {
                            $profitDetails = $exchange->takeLong($quantity, $leverage);
                        } else if ($side == "short") {
                            $profitDetails = $exchange->takeShort($quantity, $leverage);
                        }
                    } else if ($bot->strategy_mode === "short") {
                        // Take profit short
                        if ($side == "long") {
                            $profitDetails = $exchange->takeLong($quantity, $leverage);
                        } else if ($side == "short") {
                            $profitDetails = $exchange->takeShort($quantity, $leverage);
                        }
                    }

                    $profit = $profitDetails['profit'];
                    $trade_price = $profitDetails['order_price'];
                    $quantity = $profitDetails['quantity'];

                    if ($wallet->fee >= $gasFee) {

                        if ($side == "short") {
                            $type = "buy";
                        } else {
                            $type = "sell";
                        }

                        $this->takeProfit($profit, $bot, $trade_price, $quantity, $gasFee, $wallerService, $wallet, $user, $type);
                    } else {
                        $bot->update([
                            'running'           => false,
                            'logs'              => "Fuel balance is bellow the minimum, required {$gasFee} USDT, no new trade can be executed. Refill balance to continue trade."
                        ]);
                    }

                    return;
                }

                // Stoploss
                $cap_cal = $settings->capital * $settings->stop_loss / 100;
                $cap_cal = "-" . $cap_cal;
                $cap_cal = (float)$cap_cal;

                if ($current_profit < $cap_cal) {
                    if ($bot->strategy_mode === "long") {
                        $profitDetails = $exchange->takeLong($quantity, $leverage);
                    } else if ($bot->strategy_mode === "short") {
                        $profitDetails = $exchange->takeShort($quantity, $leverage);
                    }

                    $position_amount = 0;
                    $in_position = false;
                    $buy_position = false;
                    $sell_position = false;
                    $margin_call = 0;
                    $floatingLoss = 0;
                    $trade_price = 0;
                    $quantity = 0;
                    $profit = $profitDetails['profit'];
                    $firstPrice = 0;
                    $averagePrice = $profitDetails['order_price'];

                    $tradeValues = tradeValues(
                        $position_amount,
                        $in_position,
                        $buy_position,
                        $sell_position,
                        $margin_call,
                        $floatingLoss,
                        $trade_price,
                        $quantity,
                        $profit,
                        $firstPrice,
                        $averagePrice
                    );

                    $bot->update([
                        'running'           => false,
                        'trade_values'      => json_encode($tradeValues),
                        'logs'              => "Trade closed, take profit ratio reached",
                    ]);

                    if ($bot->strategy_mode == "short") {
                        $type = "buy";
                    } else {
                        $type = "sell";
                    }

                    $this->recordTrade($bot, $trade_price, $quantity, $type, $profitDetails['profit'], false);

                    return;
                }

                // martingale
                $margin_limit = (int) $settings->margin_limit;
                $margin_call  = (int)$trade_values->margin_calls;

                $price_drop = explode("|", $settings->price_drop);
                $m_ratio = explode("|", $settings->m_ratio);

                if ($margin_limit > 0 && $margin_limit !=  $margin_call) {

                    $order = null;

                    $cal = ((float) $first_price * (float)$price_drop[$margin_call]) / 100;

                    if ($bot->strategy_mode === "long") {
                        $cal2 = (float) $first_price - $cal;
                    } else if ($bot->strategy_mode === "short") {
                        $cal2 = (float) $first_price + $cal;
                    }

                    $trade_price = (float) $exchange->fetchTicker();

                    $qty = (float) $settings->first_buy * (float) $m_ratio[$margin_call] / $trade_price;

                    $tradec = (float)$settings->first_buy * (float)$m_ratio[$margin_call];

                    $options = [
                        "newClientOrderId" => "x-zcYWaQcS",
                    ];

                    // if kucoin
                    if ($bot->exchange->slug == "kucoin") {
                        $lot =(float) $exchange->fetchMarkets();

                        $qty = $tradec / $trade_price;

                        $qty = $qty / $lot;

                        $qty = $qty * $leverage;
                    }

                    if ($bot->strategy_mode === "long") {
                        if ($trade_price < $cal2) {
                            if ($balance['free'] > $tradec) {
                                $order = $exchange->createMarketBuyOrder($qty, $options);
                            } else {
                                $bot->update([
                                    'running'           => false,
                                    'logs'              => "Your exchange account does not have sufficient balance to proceed with the next Martingale entry."
                                ]);
                            }
                        }
                    } else if ($bot->strategy_mode === "short") {
                        if ($trade_price >= $cal2) {
                            if ($balance['free'] > $tradec) {
                                $order = $exchange->createMarketSellOrder($qty, $options);
                            } else {
                                $bot->update([
                                    'running'           => false,
                                    'logs'              => "Your exchange account does not have sufficient balance to proceed with the next Martingale entry."
                                ]);
                            }
                        }
                    }

                    if (!empty($order)) {
                        $quantity = $order['quantity'];
                        $position_amount = $order['position_amount'];
                        $trade_price = $order['trade_price'];
                        $orderId = $order['order_id'];

                        $fetchOrder = $exchange->fetchOrder($orderId);

                        sleep(3);

                        $qtyusdt = $fetchOrder['qtyusdt'];

                        $qtyusdt = (float) $qtyusdt / $leverage;

                        $quantity = $fetchOrder['quantity'] + $quantity;

                        $position_amount = $position_amount + $qtyusdt;

                        $positions = $exchange->getPositions();

                        $avg_price = $positions['average_price'];
                        $profit = $positions['current_profit'];
                        $floating_loss = $positions['floating_loss'];

                        $margin_call = $margin_call + 1;

                        $position_amount = $position_amount;
                        $in_position = true;
                        $buy_position = false;
                        $sell_position = false;
                        $margin_call = $margin_call;
                        $floatingLoss = $floating_loss;
                        $trade_price = $trade_price;
                        $quantity = $quantity;
                        $profit = $profit;
                        $firstPrice = $trade_values->first_price;
                        $avg_price = $avg_price;

                        $tradeValues = tradeValues(
                            $position_amount,
                            $in_position,
                            $buy_position,
                            $sell_position,
                            $margin_call,
                            $floatingLoss,
                            $trade_price,
                            $quantity,
                            $profit,
                            $firstPrice,
                            $avg_price
                        );

                        $bot->update([
                            'running'           => false,
                            'trade_values'      => json_encode($tradeValues),
                            'logs'              => "No {$margin_call} martingale buy successfully filled."
                        ]);

                        // record trade history
                        if ($bot->strategy_mode === "long") {
                            $this->recordTrade($bot, $trade_price, $quantity, "buy", $profit, false);
                        } else if ($bot->strategy_mode === "short") {
                            $this->recordTrade($bot, $trade_price, $quantity, "sell", $profit, false);
                        }
                    }

                    return;
                }
            }
        } catch (\Exception $e) {

            $responseString = $e->getMessage();

            // Find the position of the first curly brace
            $bracePosition = strpos($responseString, '{');

            if ($bracePosition !== false) {
                // Extract the JSON string
                $jsonString = substr($responseString, $bracePosition);

                // Decode the JSON string
                $responseArray = json_decode($jsonString, true);

                if ($responseArray !== null && isset($responseArray['msg'])) {
                    $errorMessage = $responseArray['msg'];

                    // Handle InsufficientFunds exception
                    $bot->update([
                        'started' => false,
                        'running' => false,
                        'logs'     => $errorMessage,
                    ]);
                } else if ($responseArray !== null && isset($responseArray['retMsg'])) {
                    $errorMessage = $responseArray['retMsg'];
                    // Now $errorMessage contains the value of "msg"
                    $bot->update([
                        'started' => false,
                        'running' => false,
                        'logs'     => $errorMessage,
                    ]);
                }
            }

            // sendToLog($e);
            logger($e);

            return;
        }
    }

    function takeProfit($profit, $bot, $trade_price, $quantity, $gasFee, $wallerService, $wallet, $user, $type = "sell")
    {

        $position_amount = 0;
        $in_position = false;
        $buy_position = false;
        $sell_position = false;
        $margin_calls = 0;
        $floatingLoss = 0;
        $profit = $profit;
        $quantity = $quantity;
        $trade_price = $trade_price;
        $firstPrice = 0;
        $average_price = $trade_price;

        $tradeValues = tradeValues($position_amount, $in_position, $buy_position, $sell_position, $margin_calls, $floatingLoss, $trade_price, $quantity, $profit, $firstPrice, $average_price);

        $bot->update([
            'running'           => false,
            'trade_values'      => json_encode($tradeValues),
            'logs'              => "Trade closed, take profit ratio reached",
        ]);

        // record profit
        if ($profit >= 0.00001) {

            $this->recordTrade($bot, $trade_price, $quantity, $type, $profit, true);

            $profitRecord = ProfitRecord::where('user_id', $bot->user_id)->first();

            // record todays and total profit
            if ($profitRecord) {
                $profitRecord->update([
                    'today_profit' => $profit + $profitRecord->today_profit,
                    'total_profit' => $profit + $profitRecord->total_profit
                ]);
            } else {
                ProfitRecord::create([
                    'user_id' => $bot->user_id,
                    'today_profit' => $profit,
                    'total_profit' => $profit
                ]);
            }

            // remove trade fee
            $fee = $profit * $gasFee / 100;

            $wallet->update([
                'fee'  => $wallet->fee - $fee,
            ]);

            // profit sharings
            $level1 = User::where('username', $user->ref)->first();
            $level2 = User::where('username', $user->level2)->first();
            $level3 = User::where('username', $user->level3)->first();

            if ($level1) {
                $teamfee = $profit * 5 / 100;

                $wallerService->updateBalance($level1->id, $teamfee);

                $wallerService->recordReward($level1->id, $user->id, $teamfee);
            }

            if ($level2) {
                $teamfee = $profit * 3 / 100;

                $wallerService->updateBalance($level2->id, $teamfee);

                $wallerService->recordReward($level2->id, $user->id, $teamfee);
            }

            if ($level3) {
                $teamfee = $profit * 2 / 100;

                $wallerService->updateBalance($level3->id, $teamfee);

                $wallerService->recordReward($level3->id, $user->id, $teamfee);
            }
        }
    }

    function recordTrade($bot, $trade_price, $quantity, $type, $profit = 0, $is_profit = false)
    {
        TradeHistory::create([
            'user_id'       => $bot->user_id,
            'exchange_id'   => $bot->exchange_id,
            'market'        => $bot->market->name,
            'trade_price'   => $trade_price,
            'profit'        => $profit,
            'quantity'      => $quantity,
            'is_profit'     => $is_profit,
            'trade_type'    => $bot->trade_type,
            'type'          => $type,
        ]);
    }
}
