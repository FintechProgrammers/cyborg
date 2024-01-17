<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TradeHistory;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserExchange;
use App\Models\Wallet;
use Illuminate\Http\Request;

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
}
