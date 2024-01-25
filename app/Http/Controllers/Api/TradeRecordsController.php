<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfitResource;
use App\Http\Resources\RewardResource;
use App\Http\Resources\TradeHistoryResource;
use App\Models\ProfitRecord;
use App\Models\Reward;
use App\Models\TradeHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TradeRecordsController extends Controller
{
    public function index(Request $request)
    {
        // Get the value of user_id from the URL parameters
        $user = $request->user;

        $tradeRecord = TradeHistory::where('user_id', $user->id)->latest()->get();

        $tradeRecord = TradeHistoryResource::collection($tradeRecord);

        return $this->sendResponse($tradeRecord);
    }

    public function profitRecord(Request $request)
    {
        // Get the value of user_id from the URL parameters
        $user = $request->user;

        $data['today_profit'] = TradeHistory::where('user_id', $user->id)->where('is_profit', true)->whereDate('created_at', Carbon::today())->sum('profit');
        $data['total_Profit'] = TradeHistory::where('user_id', $user->id)->where('is_profit', true)->sum('profit');

        // $records = ProfitRecord::where('user_id', $user->id)->first();

        // $records = new ProfitResource($records);

        return $this->sendResponse($data);
    }

    public function rewards(Request $request)
    {
        $user = $request->user;

        $rewards = Reward::where('user_id', $user->id)->latest()->get();

        $rewards = RewardResource::collection($rewards);

        return $this->sendResponse($rewards);
    }
}
