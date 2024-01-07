<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfitResource;
use App\Http\Resources\RewardResource;
use App\Http\Resources\TradeHistoryResource;
use App\Models\ProfitRecord;
use App\Models\Reward;
use App\Models\TradeHistory;
use Illuminate\Http\Request;

class TradeRecordsController extends Controller
{
    public function index(Request $request)
    {
        // Get the value of user_id from the URL parameters
        $user = $request->user;

        $tradeRecord = TradeHistory::where('user_id', $user->id)->get();

        $tradeRecord = TradeHistoryResource::collection($tradeRecord);

        return $this->sendResponse($tradeRecord);
    }

    public function profitRecord(Request $request)
    {
        // Get the value of user_id from the URL parameters
        $user = $request->user;

        $records = ProfitRecord::where('user_id', $user->id)->first();

        $records = new ProfitResource($records);

        return $this->sendResponse($records);
    }

    public function rewards(Request $request)
    {
        $user = $request->user;

        $rewards = Reward::where('user_id', $user->id)->get();

        $rewards = RewardResource::collection($rewards);

        return $this->sendResponse($rewards);
    }
}
