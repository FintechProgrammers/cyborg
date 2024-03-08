<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    protected $paginate = 10;

    function index()
    {
        $data['transactions'] = Transaction::latest()->paginate($this->paginate);
        $data['showUser'] = false;

        return view('admin.transactions.index', $data);
    }

    function show(Transaction $transaction)
    {
        $data['transaction'] = $transaction;

        return view('admin.partials._transaction_details', $data);
    }

    function withdrawals()
    {
        $data['transactions'] = Transaction::where('action', 'withdrawal')->where('status', 'pending')->where('is_manual', true)->latest()->paginate(10);
        $data['showUser'] = false;

        return view('admin.transactions.withdrawals', $data);
    }

    function approveTranasction(Transaction $transaction)
    {

        if ($transaction->is_manual) {
            $payload = json_decode($transaction->request_payload, true);

            $coin = strtoupper('usdt.trc20');

            $coinpay = new \App\Services\Gateways\Coinpay();

            // check coinpayment balance
            $balances = $coinpay->getBalance();

            if ($balances['result'][$coin]['balancef'] <  $payload['amount']) {
                sendToLog("Coinpayment insufficient balance");
                return $this->sendError("Company account do not have the balance to complete the transaction request.", [], Response::HTTP_SERVICE_UNAVAILABLE);
            }

            $response = $coinpay->withdrawal($payload);

            if (empty($response)) {
                sendToLog(["withdrawal response" => $response]);
                return $this->sendError("Unable to complete your request at the moment.", [], Response::HTTP_SERVICE_UNAVAILABLE);
            }

            if ($response['error'] !== 'ok') {
                sendToLog(["withdrawal response" => $response]);
                return $this->sendError("Unable to complete your request at the moment.", [], Response::HTTP_SERVICE_UNAVAILABLE);
            }
        } else {
            $transaction->update([
                'status' => 'complete'
            ]);
        }

        return $this->sendResponse([], "Transaction Approved successfully.");
    }

    function declineTranasction(Transaction $transaction)
    {
        $transaction->update([
            'status' => 'failed'
        ]);

        $wallet = Wallet::where('user_id', $transaction->user_id)->first();

        $wallet->update([
            'balance' => $wallet->balance + $transaction->amount
        ]);

        return $this->sendResponse([], "Transaction Declined successfully.");
    }

    public function filterTransactions(Request $request)
    {
        $data = [];

        if ($request->user) {
            $data['showUser'] = true;
        } else {
            $data['showUser'] = false;
        }

        $dateFrom = ($request->filled('date_from')) ? Carbon::parse($request->date_from)->startOfDay() : null;

        $dateTo = ($request->filled('date_to')) ? Carbon::parse($request->date_to)->endOfDay() : null;

        $query = Transaction::filterTransactions($request->ref, $request->status, $request->action, $request->type, $dateFrom, $dateTo)
            ->when(!empty($request->user), fn ($query) => $query->where('user_id', $request->user))
            ->latest();

        $transactions = $query->paginate($this->paginate);

        $data['transactions'] = $transactions;

        if ($request->user) {
            $data['totalAmount'] = $query->sum('amount');
        }

        return view('admin.transactions._table', $data);
    }
}
