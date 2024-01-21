<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exchange;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashBoardController extends Controller
{
    function index()
    {
        $data['totalUsers'] = User::count();
        $data['paidUsers']  = User::where('iseligible', 1)->count();
        $data['totalDeposit'] = Transaction::where('action', 'deposit')->sum('amount');
        $data['totalWithdrawal'] = Transaction::where('action', 'withdrawal')->sum('amount');
        $data['usersBalance'] = Wallet::sum('balance');
        $data['feeBalance'] = Wallet::sum('fee');
        $data['bindedExcahnges'] = self::bindedExchanges();

        return view('admin.dashboard.index', $data);
    }

    function getStatistics()
    {
        $deposits = Transaction::where('action', 'deposit');
        $withdrawals = Transaction::where('action', 'withdrawal');

        $data['deposits'] = $deposits->pluck('amount')->toArray();
        $data['withdrawals'] = $withdrawals->pluck('amount')->toArray();

        $startDate = Carbon::now()->subMonths(12);

        $months = [
            'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
        ];

        $monthlyDeposits = $deposits->whereBetween('created_at', [$startDate, Carbon::now()])
            ->selectRaw('MONTHNAME(created_at) as month, SUM(amount) as total_amount')
            ->groupBy('month')
            ->pluck('total_amount', 'month')
            ->toArray();

        $monthlyWithdrawals = $withdrawals->whereBetween('created_at', [$startDate, Carbon::now()])
            ->selectRaw('MONTHNAME(created_at) as month, SUM(amount) as total_amount')
            ->groupBy('month')
            ->pluck('total_amount', 'month')
            ->toArray();

        // Fill in missing months with 0
        $monthlyTotals = [];
        $montyWithdrawal = [];

        foreach ($months as $month) {
            $monthlyTotals[$month] = isset($monthlyDeposits[$month]) ? $monthlyDeposits[$month] : 0;
        }

        foreach ($months as $month) {
            $montyWithdrawal[$month] = isset($monthlyWithdrawals[$month]) ? $monthlyWithdrawals[$month] : 0;
        }

        $data['depositsInMonths'] = $monthlyTotals;

        $data['withdrawalInMonth'] = $montyWithdrawal;

        return $this->sendResponse($data);
    }

    function bindedExchanges()
    {
        $exchanges = Exchange::where('is_active', true)
            ->withCount('userExchanges')
            ->get();

        $result = $exchanges->map(function ($exchange) {
            return [
                'name' => $exchange->name,
                'count' => $exchange->user_exchanges_count,
            ];
        });

        return $result;
    }
}
