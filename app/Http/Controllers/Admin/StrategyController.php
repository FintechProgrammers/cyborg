<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\Market;
use App\Models\Strategy;
use Illuminate\Http\Request;

class StrategyController extends Controller
{
    function index()
    {
        $data['stretegy'] = Strategy::get();

        return view('admin.bot.index', $data);
    }

    function create()
    {
        $data['markets'] = Market::get();

        return view('admin.bot.create', $data);
    }

    function store(Request $request)
    {

        $request->validate([
            'market'           => 'required|exists:markets,id',
            'stop_loss'     => 'nullable|numeric|required_if:trade_type,future',
            'take_profit'      => 'required|numeric',
            'margin_limit'     => 'required|numeric|min:1',
            'market_type'      => 'required',
            'strategy_mode'     => 'nullable|required_if:market_type,future',
            'mimimum_capital'   => 'required|numeric|',
            // 'margin_ratio'     => 'required|array',
            // 'margin_ratio.*'   => 'required|integer',
            // 'price_ratio'      => 'required|array',
            // 'price_ratio.*'    => 'required|integer'
        ]);

        Strategy::create([
            'bot_name'          =>  $request->bot_name,
            'market_id'         => $request->market,
            'stop_loss'         => $request->stop_loss ? $request->stop_loss : 0,
            'margin_limit'      => $request->margin_limit,
            'take_profit'  => $request->take_profit,
            'm_ration'     => implode('|', $request->margin_ratio),
            'price_drop'   => implode('|', $request->price_drop),
            'trade_type'    => $request->market_type,
            'strategy_mode' => $request->strategy_mode? $request->strategy_mode : "short",
            'capital'   => $request->mimimum_capital,
        ]);

        return redirect()->route('admin.bot.index')->with('success', 'Bot created successfully');
    }

    function show(Strategy $strategy)
    {
        $data['strategy'] = $strategy;
        $data['markets'] = Market::get();

        return view('admin.bot.edit', $data);
    }


    function update(Request $request, Strategy $strategy)
    {
        $request->validate([
            // 'market'           => 'required|exists:markets,id',
            'stop_loss'         => 'required|numeric',
            'take_profit'       => 'required|numeric',
            'mimimum_capital'   => 'required|numeric|',
            // 'margin_limit'      => 'required|numeric',
            // 'market_type'      => 'required',
            // 'strategy_mode'     => 'nullable|required_if:market_type,future'
            // 'margin_ratio'      => 'required|array',
            // 'margin_ratio.*'    => 'required|integer',
            // 'price_ratio'      => 'required|array',
            // 'price_ratio.*'    => 'required|integer'
        ]);

        $strategy->update([
            'bot_name'  =>  $request->bot_name,
            // 'market_id'       => $request->market,
            // 'margin_limit'  => $request->margin_limit,
            'stop_loss'    => $request->stop_loss,
            'take_profit'  => $request->take_profit,
            'capital'   => $request->mimimum_capital,
            // 'm_ration'     => implode('|', $request->margin_ratio),
            // 'price_drop'   => implode('|', $request->price_drop),
            // 'trade_type'    => $request->market_type,
            // 'strategy_mode' => $request->strategy_mode
        ]);

        // get all bot that copied this strategy and update the settings

        $bots = Bot::where('copy_id', $strategy->id)->get();

        foreach ($bots as $bot) {
            $bSettings = json_decode($bot->settings);

            $settings  = [
                'stop_loss'         => $request->stop_loss,
                'take_profit'       => $request->take_profit,
                'capital'           => $bSettings->capital,
                'first_buy'         => $bSettings->first_buy,
                'margin_limit'      => $bSettings->margin_limit,
                'm_ratio'           => $bSettings->m_ratio,
                'price_drop'        => $bSettings->price_drop,
            ];

            $bot->update([
                'settings'      => json_encode($settings),
            ]);
        }

        return redirect()->route('admin.bot.index')->with('success', 'Bot updated successfully');
    }

    function destroy(Strategy $strategy)
    {
        $strategy->delete();

        return response()->json(['success' => true, 'message' => 'Bot deleted successfully']);
    }
}
