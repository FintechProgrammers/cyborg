<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    function index()
    {
        $data['transactions'] = Transaction::get();

        return view('admin.transactions.index', $data);
    }

    function show(Transaction $transaction)
    {
        $data['transaction'] = $transaction;

        return view('admin.partials._transaction_details', $data);
    }

    function withdrawals()
    {
        $data['transactions'] = Transaction::where('action', 'withdrawal')->where('status', 'pending')->get();

        return view('admin.transactions.withdrawals', $data);
    }

    function approveTranasction(Transaction $transaction)
    {
        $transaction->update([
            'status' => 'complete'
        ]);

        return $this->sendResponse([], "Transaction Approved successfully.");
    }
}
