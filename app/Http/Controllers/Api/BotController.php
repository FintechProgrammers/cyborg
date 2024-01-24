<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BotRequest;
use App\Http\Resources\BotResource;
use App\Models\Bot;
use App\Models\Exchange;
use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class BotController extends Controller
{
    function index(Request $request)
    {
        // Get the value of user_id from the URL parameters
        $user = $request->user;

        $bots = Bot::where('user_id', $user->id)->latest()->get();

        $bots = BotResource::collection($bots);

        return $this->sendResponse($bots, "Bots");
    }

    function show(Bot $bot)
    {
        $details = new BotResource($bot);

        return $this->sendResponse($details);
    }

    function create(BotRequest $request)
    {
        try {

            $user = $request->user;

            $exchange = Exchange::where('uuid', $request->exchange)->first();

            $market = Market::where('uuid', $request->market)->first();

            // check if bot already exists
            if ($request->trade_type == "future") {
                $botExists = Bot::where('user_id', $user->id)
                    ->where('trade_type', $request->trade_type)
                    ->where('exchange_id', $exchange->id)
                    ->where('market_id', $market->id)
                    ->where('strategy_mode', $request->strategy_mode)
                    ->first();
            } else {
                $botExists = Bot::where('user_id', $user->id)
                    ->where('trade_type', $request->trade_type)
                    ->where('exchange_id', $exchange->id)
                    ->where('market_id', $market->id)
                    // ->where('strategy_mode', $request->strategy_mode)
                    ->first();
            }

            if ($botExists) {
                return $this->sendError("Bot already exists.", [], 400);
            }

            $settings  = tradeSettings($request->stop_loss, $request->take_profit, $request->capital, $request->first_buy, $request->margin_limit, $request->m_ratio, $request->price_drop);

            $trade_Values = tradeValues();

            $bot = Bot::Create(
                [
                    'bot_name'      => $request->bot_name,
                    'user_id'       => $user->id,
                    'exchange_id'   => $exchange->id,
                    'market_id'     => $market->id,
                    'trade_type'    => $request->trade_type,
                    'strategy_mode' => $request->strategy_mode,
                    'settings'      => json_encode($settings),
                    'trade_Values'  => json_encode($trade_Values)
                ]
            );

            $bot = new BotResource($bot);

            return $this->sendResponse($bot, "Bot created successfully");
        } catch (\Exception $e) {
            logger(["create_bot" => $e->getMessage()]);

            return $this->sendError("Unable to complete your request at the moment.", [], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }

    function update(BotRequest $request, Bot $bot)
    {

        $exchange = Exchange::where('uuid', $request->exchange)->first();

        $market = Market::where('uuid', $request->market)->first();

        $settings  = tradeSettings($request->stop_loss, $request->take_profit, $request->capital, $request->first_buy, $request->margin_limit, $request->m_ratio, $request->price_drop);

        $trade_Values = tradeValues();

        try {
            $user = $request->user;

            $bot->update([
                'user_id'       => $user->id,
                'exchange_id'   => $exchange->id,
                'market_id'     => $market->id,
                'trade_type'    => $request->trade_type,
                'strategy_mode' => $request->strategy_mode,
                'settings'      => json_encode($settings),
                'trade_Values'  => json_encode($trade_Values)
            ]);

            $bot->refresh();

            $bot = new BotResource($bot);

            return $this->sendResponse($bot, "Bot updated successfully");
        } catch (\Exception $e) {
            logger(["create_bot" => $e->getMessage()]);

            return $this->sendError("Unable to complete your request at the moment.", [], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }

    function startBot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bot'        => 'required|string|exists:bots,uuid',
        ]);

        if ($validator->fails()) {
            return $this->sendError("Validation error", $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $trade_Values = tradeValues();

            Bot::where('uuid', $request->bot)->update([
                'started'           => true,
                'running'           => false,
                'logs'              => null,
                'trade_Values'      => json_encode($trade_Values)
            ]);

            return $this->sendResponse([], "Bot started successfully");
        } catch (\Exception $e) {
            logger(["create_bot" => $e->getMessage()]);

            return $this->sendError("Unable to complete your request at the moment.", [], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }

    function stopBot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bot'        => 'required|string|exists:bots,uuid',
        ]);

        if ($validator->fails()) {
            return $this->sendError("Validation error", $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {

            $user = $request->user;

            $trade_Values = tradeValues();

            Bot::where('uuid', $request->bot)->update([
                'started'           => false,
                'running'           => false,
                'logs'              => null,
                'trade_Values'      => json_encode($trade_Values)
            ]);

            return $this->sendResponse([], "Bot stoped successfully");
        } catch (\Exception $e) {
            logger(["create_bot" => $e->getMessage()]);

            return $this->sendError("Unable to complete your request at the moment.", [], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }

    function destroy(Bot $bot)
    {
        $bot->delete();

        return $this->sendResponse([], "Bot delete successfully");
    }
}
