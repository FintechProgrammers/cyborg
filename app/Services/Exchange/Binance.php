<?php

namespace App\Services\Exchange;

use ccxt;

class Binance
{

    protected $exchange;
    protected $trade_type;
    protected $market;

    function __construct(array $data)
    {
        $this->trade_type = $data['trade_type'];

        $this->exchange = new ccxt\binance([
            // 'enableRateLimit' => True,
            'apiKey'  => $data['apikey'],
            'secret'  => $data['secret'],
            'options' =>  [
                'defaultType' => $data['trade_type']
            ],
        ]);

        if (isset($data['market'])) {
            if ($data['trade_type'] == "spot") {
                $this->market = $data['market'] . '/USDT';
            } else {
                $this->market = $data['market'] . '/USDT';
            }
        }
    }

    function setLeverage($leverage)
    {
        $this->exchange->set_leverage($this->market, $leverage);
    }

    public function getBalance()
    {
        $freeBalance = 0.00;
        $usedBalance = 0.00;
        $totalBalance = 0.00;

        $response =  $this->exchange->fetch_balance();

        if (!empty($response)) {
            $freeBalance = $response['free']['USDT'];
            $usedBalance = $response['used']['USDT'];
            $totalBalance = $response['total']['USDT'];
        }

        return [
            'free'  => $freeBalance,
            'used'  => $usedBalance,
            'total' => $totalBalance
        ];
    }

    public function checkRate()
    {
        $check = $this->exchange->last_response_headers();
        $rate = (float) $check["x-mbx-used-weight-1m"];

        return $rate;
    }

    public function getPositions()
    {
        $positions = $this->exchange->fetch_positions([$this->market]);

        $avg_price = $positions[0]["entryPrice"];
        $position_amount = $positions[0]["initialMargin"];
        $quantity = $positions[0]["contracts"];
        $current_profit = $positions[0]['info']["unRealizedProfit"];
        // $positions[0]["unrealizedPnl"]
        $floating_loss = $positions[0]["percentage"];
        $side = $positions[0]["side"];

        return [
            'position_amount'   => $position_amount,
            'current_profit'    => $current_profit,
            'floating_loss'     => $floating_loss,
            'quantity'          => $quantity,
            'average_price'     => $avg_price,
            'side'              => $side,
        ];
    }

    public function fetchTicker()
    {
        $response = $this->exchange->fetch_ticker($this->market);

        return $response['last'];
    }

    public function createMarketBuyOrder($qty, $options = [])
    {
        $quantity = 0;
        $position_amount = 0;
        $trade_price = 0;
        $order_id = null;

        if ($this->trade_type === "spot") {
            $order = $this->exchange->create_market_buy_order($this->market, $qty);

            $position_amount = $order['info']['cummulativeQuoteQty'];

            $trade_price = $order['price'];

            $quantity = $order['info']['executedQty'];

            $order_id = $order["id"];
        } else {
            $this->exchange->set_leverage(1, $this->market);

            $this->exchange->set_margin_mode("crossed", $this->market);

            $order = $this->exchange->create_market_buy_order($this->market, $qty, $options);

            $positions = $this->exchange->fetch_positions([$this->market]);

            $position_amount = (float) $positions[0]["initialMargin"];

            $trade_price = $positions[0]["info"]["entryPrice"];

            $quantity = (float) $positions[0]["contracts"];

            $order_id = $order["id"];

            // $position_amount = (float) $order['info']["cumQuote"];
            // $trade_price = (float) $order['price'];
            // $quantity = (float) $order['info']['cumQty'];
            // $order_id = $order["info"]['orderId'];
        }


        return [
            'position_amount'   => $position_amount,
            'trade_price'       => $trade_price,
            'quantity'          => $quantity,
            'order_id'          => $order_id,
        ];
    }

    public function createMarketSellOrder($qty, $options = [])
    {

        $quantity = 0;
        $position_amount = 0;
        $trade_price = 0;

        $order_id = null;

        if ($this->trade_type === "spot") {

            $order = $this->exchange->create_market_sell_order($this->market, $qty);

            $position_amount = $order['info']['cummulativeQuoteQty'];

            $trade_price = $order['price'];

            $order_id = $order["id"];

            $quantity = $order['info']['executedQty'];
        } else {
            $this->exchange->set_leverage(1, $this->market);

            $this->exchange->set_margin_mode("crossed", $this->market);

            $order = $this->exchange->create_market_sell_order($this->market, $qty, $options);

            $positions = $this->exchange->fetch_positions([$this->market]);

            $position_amount = (float) $positions[0]["initialMargin"];

            $trade_price = $positions[0]["info"]["entryPrice"];

            $quantity = (float) $positions[0]["contracts"];

            $order_id = $order["id"];

            // $position_amount = (float) $order['info']["cumQuote"];
            // $trade_price = (float) $order['price'];
            // $quantity = (float) $order['info']['cumQty'];
            // $order_id = $order["info"]['orderId'];
        }



        return [
            'position_amount'   => $position_amount,
            'trade_price'       => $trade_price,
            'quantity'          => $quantity,
            'order_id'          => $order_id,
        ];
    }

    public function lastResponseHeaders()
    {
        $response = $this->exchange->last_response_headers();

        logger($response);

        return $response['x-mbx-used-weight-1m'];
    }

    public function myTrades($time)
    {
        $my_trades = $this->exchange->fetch_my_trades($this->market, $time);

        return $my_trades;
    }

    public function fetchOrder($orderId)
    {
        $response = $this->exchange->fetch_order($orderId, $this->market);

        $quantity = $response["info"]["executedQty"];
        $qtyusdt  = $response["info"]["cumQuote"];
        $order_price = $response["info"]["avgPrice"];

        return [
            'quantity'      => $quantity,
            'qtyusdt'       => $qtyusdt,
            'order_price'   => $order_price
        ];
    }

    public function takeLong($quantity, $leverage = 1)
    {
        // Get the current time in seconds with microsecond precision
        $t = microtime(true);

        // Convert the time to milliseconds
        $t = $t * 1000;

        // Convert to an integer
        $t = (int)$t;

        // Sleep for 3 seconds
        sleep(3);

        // create market sell order
        $options = [
            "leverage" => $leverage,
            "newClientOrderId" => "x-zcYWaQcS",
            "reduceOnly" => true,
        ];

        $order = $this->createMarketSellOrder($quantity, $options);

        $order_id = $order['order_id'];
        // Sleep for 3 seconds
        sleep(3);

        $myTrades = $this->myTrades($t);

        $profit = 0;

        foreach ($myTrades as $lastTrade) {
            if ($lastTrade['info']['orderId'] == $order_id) {
                $p = $lastTrade["info"]["realizedPnl"];
                $profit = $profit + $p;
            }
        }

        $getorder = $this->fetchOrder($order_id);

        $fee = 0.06 * $leverage;

        $fee_call = $profit * $fee / 100;

        $profit = $profit - $fee_call;

        return [
            'profit'        => $profit,
            'quantity'      => $getorder['quantity'],
            'qtyusdt'       => $getorder['qtyusdt'],
            'order_price'   => $getorder['order_price']
        ];
    }

    public function takeShort($quantity, $leverage = 1)
    {
        $positions = $this->getPositions();

        $average_price = $positions['average_price'];
        $profit = $positions['current_profit'];

        // Get the current time in seconds with microsecond precision
        $t = microtime(true);

        // Convert the time to milliseconds
        $t = $t * 1000;

        // Convert to an integer
        $t = (int)$t;

        // Sleep for 3 seconds
        sleep(3);

        // create market sell order
        $options = [
            "leverage"         => $leverage,
            "newClientOrderId" => "x-zcYWaQcS",
            "reduceOnly"       => true,
        ];

        $order = $this->createMarketBuyOrder($quantity, $options);

        $order_id = $order['order_id'];

        // Sleep for 3 seconds
        sleep(3);

        $myTrades = $this->myTrades($t);

        $profit = 0;

        foreach ($myTrades as $lastTrade) {
            if ($lastTrade['info']['orderId'] == $order_id) {
                $p = $lastTrade["info"]["realizedPnl"];
                $profit = $profit + (float) $p;
            }
        }

        $getorder = $this->fetchOrder($order_id);

        $fee = 0.06 * $leverage;

        $fee_call = (float) $profit * $fee / 100;
        $profit = $profit - $fee_call;

        return [
            'profit'        => $profit,
            'quantity'      => $getorder['quantity'],
            'qtyusdt'       => $getorder['qtyusdt'] / $leverage,
            'order_price'   => $getorder['order_price']
        ];
    }
}
