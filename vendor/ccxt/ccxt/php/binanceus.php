<?php

namespace ccxt;

// PLEASE DO NOT EDIT THIS FILE, IT IS GENERATED AND WILL BE OVERWRITTEN:
// https://github.com/ccxt/ccxt/blob/master/CONTRIBUTING.md#how-to-contribute-code

use Exception; // a common import
use ccxt\abstract\binanceus as binance;

class binanceus extends binance {

    public function describe() {
        return $this->deep_extend(parent::describe(), array(
            'id' => 'binanceus',
            'name' => 'Binance US',
            'countries' => array( 'US' ), // US
            'rateLimit' => 50, // 1200 req per min
            'certified' => false,
            'pro' => true,
            'urls' => array(
                'logo' => 'https://user-images.githubusercontent.com/1294454/65177307-217b7c80-da5f-11e9-876e-0b748ba0a358.jpg',
                'api' => array(
                    'web' => 'https://www.binance.us',
                    'sapi' => 'https://api.binance.us/sapi/v1',
                    'wapi' => 'https://api.binance.us/wapi/v3',
                    'public' => 'https://api.binance.us/api/v3',
                    'private' => 'https://api.binance.us/api/v3',
                ),
                'www' => 'https://www.binance.us',
                'referral' => 'https://www.binance.us/?ref=35005074',
                'doc' => 'https://github.com/binance-us/binance-official-api-docs',
                'fees' => 'https://www.binance.us/en/fee/schedule',
            ),
            'fees' => array(
                'trading' => array(
                    'tierBased' => true,
                    'percentage' => true,
                    'taker' => $this->parse_number('0.001'), // 0.1% trading fee, zero fees for all trading pairs before November 1.
                    'maker' => $this->parse_number('0.001'), // 0.1% trading fee, zero fees for all trading pairs before November 1.
                ),
            ),
            'options' => array(
                'fetchMarkets' => array( 'spot' ),
                'defaultType' => 'spot',
                'quoteOrderQty' => false,
            ),
            'has' => array(
                'CORS' => null,
                'spot' => true,
                'margin' => false,
                'swap' => false,
                'future' => null,
                'option' => false,
                'addMargin' => false,
                'closeAllPositions' => false,
                'closePosition' => false,
                'createReduceOnlyOrder' => false,
                'fetchBorrowInterest' => false,
                'fetchBorrowRate' => false,
                'fetchBorrowRateHistories' => false,
                'fetchBorrowRateHistory' => false,
                'fetchBorrowRates' => false,
                'fetchBorrowRatesPerSymbol' => false,
                'fetchFundingHistory' => false,
                'fetchFundingRate' => false,
                'fetchFundingRateHistory' => false,
                'fetchFundingRates' => false,
                'fetchIndexOHLCV' => false,
                'fetchIsolatedPositions' => false,
                'fetchLeverage' => false,
                'fetchLeverageTiers' => false,
                'fetchMarketLeverageTiers' => false,
                'fetchMarkOHLCV' => false,
                'fetchOpenInterestHistory' => false,
                'fetchPosition' => false,
                'fetchPositions' => false,
                'fetchPositionsRisk' => false,
                'fetchPremiumIndexOHLCV' => false,
                'reduceMargin' => false,
                'setLeverage' => false,
                'setMargin' => false,
                'setMarginMode' => false,
                'setPositionMode' => false,
            ),
            'api' => array(
                'public' => array(
                    'get' => array(
                        'exchangeInfo' => 10,
                        'ping' => 1,
                        'time' => 1,
                        'depth' => array( 'cost' => 1, 'byLimit' => array( array( 100, 1 ), array( 500, 5 ), array( 1000, 10 ), array( 5000, 50 ) ) ),
                        'trades' => 1,
                        'aggTrades' => 1,
                        'historicalTrades' => 5,
                        'klines' => 1,
                        'ticker/price' => array( 'cost' => 1, 'noSymbol' => 2 ),
                        'avgPrice' => 1,
                        'ticker/bookTicker' => array( 'cost' => 1, 'noSymbol' => 2 ),
                        'ticker/24hr' => array( 'cost' => 1, 'noSymbol' => 40 ),
                        'ticker' => array( 'cost' => 2, 'noSymbol' => 100 ),
                    ),
                ),
                'private' => array(
                    'get' => array(
                        'status' => 1,
                    ),
                ),
            ),
        ));
    }
}