<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TradeHistory;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    function index()
    {
        $data['trades'] = TradeHistory::latest()->paginate(10);
        $data['showUser'] = false;

        return view('admin.trades.index', $data);
    }

    function show(TradeHistory $tradeHistory)
    {
    }

    public function filterTrades(Request $request)
    {
        $data = [];

        if ($request->user) {
            $data['showUser'] = true;
        } else {
            $data['showUser'] = false;
        }

        $tradeStatus = $request->trade_status;

        $query = TradeHistory::filterTrades($request->username, $request->exchange, $request->trade_type, $request->type, $request->trade_status)
            ->when(!empty($request->user), fn ($query) => $query->where('user_id', $request->user))
            ->when(!empty($tradeStatus), function ($query) use ($tradeStatus) {
                if ($tradeStatus == "profit") {
                    return $query->where('is_profit', true);
                }
            })
            ->latest();

        $trades = $query->paginate(10);

        $data['trades'] = $trades;

        return view('admin.trades._trades_table', $data);
    }
}
