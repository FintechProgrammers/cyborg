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
}
