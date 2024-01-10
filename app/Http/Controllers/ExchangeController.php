<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExchangeBindRequest;
use App\Http\Resources\BindedExchangeResource;
use App\Http\Resources\ExchangeResource;
use App\Http\Resources\MarketResource;
use App\Models\Exchange;
use App\Models\UserExchange;
use Illuminate\Http\Request;

class ExchangeController extends Controller
{
    function index(Request $request)
    {
        $exchanges = ExchangeResource::collection(Exchange::where('is_active', 1)->get());

        return $this->sendResponse($exchanges, "List of active exchanges.");
    }

    function bind(ExchangeBindRequest $request)
    {
        try {
            $exchange = Exchange::whereUuid($request->exchange)->first();

            if (!$exchange) {
                return $this->sendError("Invalid exchange.", [], 402);
            }

            if (!$exchange->is_active) {
                return $this->sendError("{$exchange->name} is not active.", [], 402);
            }

            $user = $request->user;

            $setupData = [
                'apikey'        => $request->api_key,
                'secret'        => $request->secret,
                'trade_type'    =>  "spot"
            ];

            // connect to exchange
            $exchangeKey = ucfirst($exchange->slug);

            $exchangeService = "\\App\\Services\\Exchange\\{$exchangeKey}";

            $exchangeService = new $exchangeService($setupData);

            $spotBalance = $exchangeService->getBalance();

            if ($exchange->futures) {

                $setupData['trade_type'] = "future";

                $exchangeFuturesService = new $exchangeService($setupData);

                $futuresBalance = $exchangeFuturesService->getBalance();
            }

            $binded = UserExchange::updateOrCreate(
                [
                    'user_id'       => $user->id,
                    'exchange_id' => $exchange->id
                ],
                [
                    'user_id'           => $user->id,
                    'exchange_id'       => $exchange->id,
                    'api_key'           => $request->api_key,
                    'api_secret'        => $request->secret,
                    'api_passphrase'    => $request->password,
                    'spot_balance'      => !empty($spotBalance['total']) ? $spotBalance['total'] : 0.00,
                    'future_balance'    => !empty($futuresBalance['total']) ? $futuresBalance['total'] : 0.00,
                    'is_binded'         => (bool) $request->bind
                ]
            );

            $bindeds = new BindedExchangeResource($binded);

            return $this->sendResponse($bindeds, "{$exchange->name} binded successfully.", 201);
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
                    // Now $errorMessage contains the value of "msg"
                    return $this->sendError($errorMessage, [], 500);
                } else if ($responseArray !== null && isset($responseArray['retMsg'])) {
                    $errorMessage = $responseArray['retMsg'];
                    // Now $errorMessage contains the value of "msg"
                    return $this->sendError($errorMessage, [], 500);
                } else {
                    logger($e->getMessage());
                    return $this->sendError("Your request cannot be completed at the momment.", [], 500);
                }
            } else {
                logger($e->getMessage());
                return $this->sendError("Your request cannot be completed at the momment.", [], 500);
            }
        }
    }

    function bindedExchange(Request $request)
    {
        $user = $request->user;

        $binded = UserExchange::where('user_id', $user->id)->get();

        $binded = BindedExchangeResource::collection($binded);

        return $this->sendResponse($binded, "Binded exchange.", 200);
    }

    function markets()
    {
        $market = \App\Models\Market::get();

        $market = MarketResource::collection($market);

        return $this->sendResponse($market);
    }
}
