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
}
