<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CopyRequest;
use App\Http\Resources\StrategyResource;
use App\Models\Bot;
use App\Models\Exchange;
use App\Models\Strategy;
use Illuminate\Http\Request;

class StrategyController extends Controller
{
    function index()
    {
        $strategies = Strategy::get();

        $strategies = StrategyResource::collection($strategies);

        return $this->sendResponse($strategies, "Strategies available for copy", 200);
    }

    function copyStrategy(CopyRequest $request)
    {
        try {
            $user = $request->user;

            $strategy = Strategy::whereUuid($request->strategy)->first();

            $exchange = Exchange::where('uuid', $request->exchange)->first();

            // check if bot already exists
            if ($strategy->trade_type == "future") {
                $botExists = Bot::where('user_id', $user->id)
                    ->where('trade_type', $strategy->trade_type)
                    ->where('exchange_id', $exchange->id)
                    ->where('market_id', $strategy->market_id)
                    ->where('strategy_mode', $strategy->strategy_mode)
                    ->first();
            } else {
                $botExists = Bot::where('user_id', $user->id)
                    ->where('trade_type', $strategy->trade_type)
                    ->where('exchange_id', $exchange->id)
                    ->where('market_id', $strategy->market_id)
                    ->first();
            }

            if ($botExists) {
                return $this->sendError("Bot already exists.", [], 400);
            }

            if ($request->capital < $strategy->capital) {
                return $this->sendError("Capital should not be less than " . $strategy->capital, [], 422);
            }

            if ($request->capital >= 500) {
                $numbers = explode("|", $strategy->m_ration);

                $entries = array_sum($numbers);

                $firstbuy_amount = $request->capital / ($entries + 1);

                $firstbuy_amount = number_format($firstbuy_amount, 0);
            } else {
                $firstbuy_amount = 15;
            }

            $settings  = tradeSettings($strategy->stop_loss, $strategy->take_profit, $request->capital, $firstbuy_amount, $strategy->margin_limit, $strategy->m_ration, $strategy->price_drop);

            $trade_Values = tradeValues();

            $bot = Bot::Create([
                'bot_name'      => $strategy->bot_name,
                'user_id'       => $user->id,
                'exchange_id'   => $exchange->id,
                'market_id'     => $strategy->market_id,
                'trade_type'    => $strategy->trade_type,
                'strategy_mode' => $strategy->strategy_mode,
                'settings'      => json_encode($settings),
                'trade_Values'  => json_encode($trade_Values),
                'copy_id'       => $strategy->id,
                'is_copied'     => true
            ]);

            return $this->sendResponse([], "Strategy copied successfully.");
        } catch (\Exception $e) {
            sendToLog($e);
            return $this->sendError("Your request could be completed at the moment.", [], 500);
        }
    }
}
