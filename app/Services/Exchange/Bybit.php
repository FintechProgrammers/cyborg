<?php

namespace App\Services\Exchange;

use ccxt;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Bybit
{

    protected $exchange;
    protected $trade_type;
    protected $market;
    protected $apiKey;
    protected $secret;
    protected $apiTradeType;

    function __construct(array $data)
    {
        $this->trade_type = $data['trade_type'];

        $this->apiTradeType = $data['trade_type'] === "spot" ? "spot" : "contract";

        $this->secret = $data['secret'];
        $this->apiKey = $data['apikey'];

        $this->exchange = new ccxt\bybit([
            // 'enableRateLimit' => True,
            'apiKey'  => $data['apikey'],
            'secret'  => $data['secret'],
            'options' =>  [
                'defaultType' => $data['trade_type'],
                'createMarketBuyOrderRequiresPrice' => false
            ],
        ]);

        if (isset($data['market'])) {
            $this->market = $data['market'] . 'USDT';
        }
    }

    function setLeverage($leverage)
    {
        $this->exchange->set_leverage($this->market, $leverage);
    }

    function getBalanceV3()
    {
        $response = self::handle("/{$this->apiTradeType}/v3/private/account", "GET");

        return $response;
    }

    public function getBalance()
    {
        $freeBalance = 0.00;
        $usedBalance = 0.00;
        $totalBalance = 0.00;

        $response =  $this->exchange->fetch_balance();

        if (!empty($response)) {
            $freeBalance = !empty($response['free']['USDT']) ? $response['free']['USDT'] : 0.00;
            $usedBalance = !empty($response['used']['USDT']) ? $response['used']['USDT'] : 0.00;
            $totalBalance = !empty($response['total']['USDT']) ? $response['total']['USDT'] : 0.00;
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

        // $param = "symbol={$this->market}";

        // $response2 = self::handle("/{$this->apiTradeType}/v3/public/quote/ticker/24hr?$param", "GET", [], $param);
        return $response['last'];
    }

    public function createMarketBuyOrder($qty, $options = [])
    {
        $quantity = 0;
        $position_amount = 0;
        $trade_price = 0;
        $order_id = null;

        if ($this->trade_type === "spot") {

            // $body = [
            //     'symbol'    => $this->market,
            //     'orderQty'  => $qty,
            //     'side'      => 'BUY',
            //     'orderType' => 'MARKET'
            // ];

            // $response = self::handle("/{$this->apiTradeType}/v3/private/order", "POST", $body);

            // logger($response);

            // dd($response);

            $order = $this->exchange->create_market_buy_order($this->market, $qty);

            $order_id = $order['info']['orderId'];

            sleep(3);

            $order = $this->exchange->fetch_order($order_id, $this->market);

            $position_amount = $order['info']['cumExecValue'];

            $trade_price = $order['average'];

            $quantity = $order['info']['cumExecQty'];
        } else {
            $this->exchange->set_leverage(1, $this->market);

            $order = $this->exchange->create_order($this->market, 'market', 'buy', $qty);

            $order_id = $order['info']['orderId'];

            sleep(3);

            $order = $this->exchange->fetch_order($order_id, $this->market);

            $position_amount = $order['info']['cumExecValue'];

            $position_amount = $position_amount / 1;

            $trade_price = $order['average'];
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

            $options = [
                'createMarketBuyOrderRequiresPrice' => false
            ];

            $order = $this->exchange->create_order($this->market, 'market', 'sell', $qty, null, $options);

            $order_id = $order['info']['orderId'];

            sleep(3);

            $order = $this->exchange->fetch_order($order_id, $this->market);

            $position_amount = $order['info']['cumExecValue'];

            $trade_price = $order['average'];

            $quantity = $order['info']['cumExecQty'];
        } else {
            $this->exchange->set_leverage(1, $this->market);

            $order = $this->exchange->create_order($this->market, 'market', 'sell', $qty);

            $order_id = $order['info']['orderId'];

            sleep(3);

            $order = $this->exchange->fetch_order($order_id, $this->market);

            $position_amount = $order['info']['cumExecValue'];

            $position_amount = $position_amount / 1;

            $trade_price = $order['average'];
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

    function generateSignature($timestamp, $apiKey, $recvWindow, $queryString = null, $jsonBodyString = null)
    {

        // Construct the string to sign
        $stringToSign = $timestamp . $apiKey . $recvWindow;

        if ($queryString !== null) {
            $stringToSign .= $queryString;
        }


        if ($jsonBodyString !== null) {
            $stringToSign .= $jsonBodyString;
        }

        // Use HMAC_SHA256 algorithm to sign the string
        $signature = hash_hmac('sha256', $stringToSign, $this->secret);

        return $signature;
    }

    public function handle($uri, $method = "POST", $body = [], $queryString =  null)
    {
        $timestamp = time() * 1000;

        $recvWindow = 6000;
        $jsonBodyString =  !empty($body) ? json_encode($body) : null;

        $signature = self::generateSignature($timestamp, $this->apiKey, $recvWindow, $queryString, $jsonBodyString);

        $headers = [
            'X-BAPI-SIGN' => $signature,
            'X-BAPI-API-KEY' => $this->apiKey,
            'X-BAPI-TIMESTAMP' => $timestamp,
            'X-BAPI-RECV-WINDOW' => $recvWindow,
            'Content-Type' => 'application/json',
        ];

        $reqBody = ['headers' => $headers];

        if (!empty($body)) {
            $reqBody['json'] = $body;
        }

        // Make the Guzzle HTTP POST request
        $client = new Client(['base_uri' => 'https://api.bybit.com']);

        try {

            $response = $client->request($method, $uri, $reqBody);

            // Handle the response as needed
            $statusCode = $response->getStatusCode();
            $responseBody = $response->getBody()->getContents();

            return json_decode($responseBody, true);
        } catch (Exception $e) {
            // Handle request exception

            logger(['bybit error' => $e]);
        }
    }
}
