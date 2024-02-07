<?php

namespace App\Services\Exchange;

use ccxt;

class Kucoin
{

    protected $exchange;
    protected $trade_type;
    protected $market;

    function __construct(array $data)
    {
        $this->trade_type = $data['trade_type'];

        if ($data['trade_type'] == "spot") {
            $this->exchange = new  ccxt\kucoin([
                // 'enableRateLimit' => True,
                'apiKey'    => $data['apikey'],
                'secret'    => $data['secret'],
                'password'  => $data['password'],
                'options' =>  [
                    'defaultType'             => $data['trade_type']
                ],
            ]);
        } else {
            $this->exchange = new  ccxt\kucoinfutures([
                // 'enableRateLimit' => True,
                'apiKey'    => $data['apikey'],
                'secret'    => $data['secret'],
                'password'  => $data['password'],
                'options' =>  [
                    'defaultType' => $data['trade_type'],
                    "adjustForTimeDifference" => true,
                    "marginMode"              => "cross",
                ],
            ]);
        }

        if (isset($data['market'])) {
            if ($data['trade_type'] == "spot") {
                $this->market = $data['market'] . '/USDT';
            } else {
                $this->market = $data['market'] . 'USDTM';
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

        $avg_price = $positions[0]["info"]["avgEntryPrice"];
        $position_amount = $positions[0]["initialMargin"];
        $quantity = $positions[0]["contracts"];
        $current_profit = $positions[0]["unrealizedPnl"];
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

    public function fetchMarkets()
    {
        $lots = $this->exchange->fetch_markets([
            "symbol" => $this->market
        ]);

        foreach ($lots as $lot) {
            if ($lot['id'] == $this->market) {
                $lot = $lot['contractSize'];
                break;
            }
        }

        return $lot;
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

            $order_id = $order["info"]['orderId'];

            sleep(3);

            $getorder = $this->exchange->fetch_order($order_id);

            $quantity = $getorder['amount'];

            $position_amount = $getorder['cost'];

            $trade_price = $getorder['price'];
        } else {

            $order = $this->exchange->create_market_buy_order($this->market, $qty, $options);

            $order_id = $order["id"];

            sleep(3);

            $getorder = $this->exchange->fetch_order($order_id, $this->market);

            $position_amount = (float) $getorder["cost"];

            $trade_price = $getorder["price"];

            $quantity = (float) $getorder["filled"];
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

            $order_id = $order["info"]['orderId'];

            sleep(3);

            $getorder = $this->exchange->fetch_order($order_id);

            $quantity = $getorder['amount'];

            $position_amount = $getorder['cost'];

            $trade_price = $getorder['price'];
        } else {

            $order = $this->exchange->create_market_sell_order($this->market, $qty, $options);

            $order_id = $order["id"];

            sleep(3);

            $getorder = $this->exchange->fetch_order($order_id, $this->market);

            $position_amount = (float) $getorder["cost"];

            $trade_price = $getorder["price"];

            $quantity = (float) $getorder["filled"];
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
        $positions = $this->getPositions();

        $average_price = $positions['average_price'];
        $profit = $positions['current_profit'];

        // create market sell order
        $options = [
            "leverage"         => $leverage,
            "newClientOrderId" => "x-zcYWaQcS",
            "reduceOnly"       => true,
        ];


        $fee = 0.08 * $leverage;

        $fee_call = $profit * $fee / 100;

        $profit = $profit - $fee_call;

        $order = $this->createMarketSellOrder($quantity, $options);

        $order_id = $order['order_id'];

        sleep(3);

        $getorder = $this->exchange->fetch_order($order_id);

        $quantity = $getorder['filled'];

        $trade_price = $getorder['price'];

        $qtyusdt = $getorder['cost'] / $leverage;

        return [
            'profit'        => $profit,
            'quantity'      => $quantity,
            'qtyusdt'       => $qtyusdt,
            'order_price'   => $trade_price
        ];
    }

    public function takeShort($quantity, $leverage = 1)
    {
        $positions = $this->getPositions();

        $average_price = $positions['average_price'];
        $profit = $positions['current_profit'];

        $fee = 0.08 * $leverage;

        $fee_call = (float) $profit * $fee / 100;
        $profit = $profit - $fee_call;

        // create market buy order
        $options = [
            "leverage"         => $leverage,
            "newClientOrderId" => "x-zcYWaQcS",
            "reduceOnly"       => true,
        ];

        $order = $this->createMarketBuyOrder($quantity, $options);

        $order_id = $order['order_id'];

        sleep(3);

        $getorder = $this->exchange->fetch_order($order_id);

        $quantity = $getorder['filled'];

        $trade_price = $getorder['price'];

        $qtyusdt = $getorder['cost'] / $leverage;

        return [
            'profit'        => $profit,
            'quantity'      => $quantity,
            'qtyusdt'       => $qtyusdt,
            'order_price'   => $trade_price
        ];
    }
}
