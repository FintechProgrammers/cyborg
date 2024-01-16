<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

        return view('admin.bot.create',$data);
    }

    function store(Request $request)
    {

        $request->validate([
            'market'           => 'required|exists:markets,id',
            'stop_loss'        => 'required|numeric',
            'take_profit'      => 'required|numeric',
            'margin_limit'     => 'required|numeric|min:1',
            'margin_ratio'     => 'required|array',
            'margin_ratio.*'   => 'required|integer',
            'price_ratio'      => 'required|array',
            'price_ratio.*'    => 'required|integer'
        ]);

        Strategy::create([
            'market'       => $request->market,
            'stop_loss'    => $request->stop_loss,
            'take_profit'  => $request->take_profit,
            'm_ration'     => implode('|', $request->margin_ratio),
            'price_drop'   => implode('|', $request->price_drop)
        ]);

        return redirect()->route('admin.bot.index')->with('success', 'Bot created successfully');
    }

    function show(Strategy $strategy)
    {
        $data['strategy'] = $strategy;
        $data['markets'] = Market::get();

        return view('admin.banner.edit', $data);
    }


    function update(Request $request, Strategy $strategy)
    {
        $request->validate([
            'market'           => 'required|exists:markets,id',
            'stop_loss'         => 'required|numeric',
            'take_profit'       => 'required|numeric',
            'margin_limit'      => 'required|numeric',
            'margin_ratio'      => 'required|array',
            'margin_ratio.*'    => 'required|integer',
            'price_ratio'      => 'required|array',
            'price_ratio.*'    => 'required|integer'
        ]);

        $strategy->update([
            'market'       => $request->market,
            'stop_loss'    => $request->stop_loss,
            'take_profit'  => $request->take_profit,
            'm_ration'     => implode('|', $request->margin_ratio),
            'price_drop'   => implode('|', $request->price_drop)
        ]);

        return redirect()->route('admin.bot.index')->with('success', 'Bot updated successfully');
    }

    function destroy(Strategy $strategy)
    {
        $strategy->delete();

        return response()->json(['success' => true, 'message' => 'Ads Banner deleted successfully']);
    }
}
