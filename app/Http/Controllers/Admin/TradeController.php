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
}
