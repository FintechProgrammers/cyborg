<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BotRequest;
use App\Models\Bot;
use App\Models\Exchange;
use App\Models\Market;
use App\Models\Reward;
use App\Models\TradeHistory;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserExchange;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserManagementController extends Controller
{
    function index()
    {
        $data['users'] = User::get();

        return view('admin.users.index', $data);
    }

    function show(User $user)
    {
        $data['user'] = $user;
        $data['wallet'] = Wallet::where('user_id', $user->id)->first();
        $data['exchanges'] = UserExchange::where('user_id', $user->id)->get();
        $data['transactions'] = Transaction::where('user_id', $user->id)->get();
        $data['trades'] = TradeHistory::where('user_id', $user->id)->get();
        $data['rewards'] = Reward::where('user_id', $user->id)->get();
        $data['todayProfit'] = TradeHistory::where('user_id', $user->id)->where('is_profit', true)->whereDate('created_at', Carbon::today())->sum('profit');
        $data['totalProfit'] = TradeHistory::where('user_id', $user->id)->where('is_profit', true)->sum('profit');
        $data['activeBots'] = Bot::where('user_id', $user->id)->where('started', true)->get();
        $data['bots'] = Bot::where('user_id', $user->id)->get();

        return view('admin.users.show', $data);
    }

    function fundForm($wallet, User $user)
    {
        $data['wallet'] = Wallet::where('user_id', $user->id)->first();
        $data['type']   = $wallet;
        $data['user']   = $user;

        return view('admin.users.form.credit-form', $data);
    }

    function debitForm($wallet, User $user)
    {
        $data['wallet'] = Wallet::where('user_id', $user->id)->first();
        $data['type']   = $wallet;
        $data['user']   = $user;

        return view('admin.users.form.debit-form', $data);
    }

    function fund(Request $request, User $user)
    {
        $request->validate([
            'amount'  => 'required|numeric|min:1'
        ]);

        $wallet = Wallet::where('user_id', $user->id)->first();

        if ($request->type == "fee") {

            $wallet->update([
                'fee' => $request->amount + $wallet->fee
            ]);

            $transaction  = [
                'user_id'       => $user->id,
                'reference'     => generateReference(),
                'coin'          => 'USDT',
                'amount'        => $request->amount,
                'type'          => 'credit',
                'action'        => 'deposit',
                'status'        => 'complete',
                'narration'     => 'Deposit to fee by administrator.'
            ];
        } else {

            $wallet->update([
                'balance' => $request->amount + $wallet->balance
            ]);

            $transaction  = [
                'user_id'       => $user->id,
                'reference'     => generateReference(),
                'coin'          => 'USDT',
                'amount'        => $request->amount,
                'type'          => 'credit',
                'action'        => 'deposit',
                'status'        => 'complete',
                'narration'     => 'Deposit to main balance by administrator.'
            ];
        }

        Transaction::create($transaction);

        return response()->json(['success' => true, 'message' => 'Wallet funded successfully.']);
    }

    function debit(Request $request, User $user)
    {
        $request->validate([
            'amount'  => 'required|numeric|min:1'
        ]);

        $wallet = Wallet::where('user_id', $user->id)->first();

        if ($request->type == "fee") {

            if ($request->amount > $wallet->fee) {
                return response()->json(['success' => false, 'message' => 'Insufficient funds.']);
            }

            $wallet->update([
                'fee' => $wallet->fee - $request->amount
            ]);

            $transaction  = [
                'user_id'       => $user->id,
                'reference'     => generateReference(),
                'coin'          => 'USDT',
                'amount'        => $request->amount,
                'type'          => 'debit',
                'action'        => 'withdrawal',
                'status'        => 'complete',
                'narration'     => 'Debit fromt fee balance by administrator.'
            ];
        } else {

            if ($request->amount > $wallet->balance) {
                return response()->json(['success' => false, 'message' => 'Insufficient funds.']);
            }

            $wallet->update([
                'balance' => $wallet->balance - $request->amount
            ]);

            $transaction  = [
                'user_id'       => $user->id,
                'reference'     => generateReference(),
                'coin'          => 'USDT',
                'amount'        => $request->amount,
                'type'          => 'debit',
                'action'        => 'withdrawal',
                'status'        => 'complete',
                'narration'     => 'Debit from main balance by administrator.'
            ];
        }

        Transaction::create($transaction);

        return response()->json(['success' => true, 'message' => 'Wallet debited successfully.']);
    }

    function createBotForm(User $user)
    {
        $data['markets'] = Market::get();
        $data['exchanges'] = UserExchange::where('user_id', $user->id)->where('is_binded', true)->get();
        $data['user'] = $user;

        return view('admin.users.bots.create', $data);
    }

    function editBotForm(User $user)
    {
        $data['markets'] = Market::get();
        $data['exchanges'] = UserExchange::where('user_id', $user->id)->get();

        return view('admin.users.bots.edit', $data);
    }

    function createBot(BotRequest $request, User $user)
    {
        try {

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

            $m_ratio = implode('|', $request->m_ratio);
            $price_drop = implode('|', $request->price_drop);

            $settings  = tradeSettings($request->stop_loss, $request->take_profit, $request->capital, $request->first_buy, $request->margin_limit, $m_ratio, $price_drop);

            $trade_Values = tradeValues();

            $data = [
                'bot_name'      => $request->bot_name,
                'user_id'       => $user->id,
                'exchange_id'   => $exchange->id,
                'market_id'     => $market->id,
                'trade_type'    => $request->trade_type,
                'settings'      => json_encode($settings),
                'trade_values'  => json_encode($trade_Values)
            ];

            if ($request->strategy_mode) {
                $data['strategy_mode'] = $request->strategy_mode;
            }

            $bot = Bot::create($data);

            return $this->sendResponse($bot, "Bot created Successfully");
        } catch (\Exception $e) {
            logger(["create_bot" => $e->getMessage()]);

            return $this->sendError("Unable to complete your request at the moment.", [], 500);
        }
    }

    function startBot(Bot $bot)
    {
        $trade_Values = tradeValues();

        $bot->update([
            'started'           => true,
            'running'           => false,
            'logs'              => null,
            'trade_Values'      => json_encode($trade_Values)
        ]);

        return $this->sendResponse([], "Bot started successfully");
    }

    function stopBot(Bot $bot)
    {
        $trade_Values = tradeValues();

        $bot->update([
            'started'           => false,
            'running'           => false,
            'logs'              => null,
            'trade_Values'      => json_encode($trade_Values)
        ]);

        return $this->sendResponse([], "Bot stoped successfully.");
    }

    function deleteBot(Bot $bot)
    {
        $bot->delete();

        return $this->sendResponse([], "Bot deleted successfully.");
    }
}
