<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    function index()
    {
        return view('admin.settings.index');
    }

    function store(Request $request)
    {
        $request->validate([
            'withdrawal_status'  => 'required',
        ]);

        Settings::first()->update([
            'withdrawal_status'     => $request->withdrawal_status,
            'automatic_withdrawal'  => $request->automatic_withdrawal,
            'minimum_widthdrawal'   => $request->minimum_withdrawal,
            'maximum_widthdrawal'   => $request->maximum_withdrawal,
            'withdrawal_fee'        => $request->minimum_fee,
            'trade_status'          => $request->trading_status,
            'trade_fee'             => $request->trading_fee,
            'apple_pay'             => $request->apple_pay == 'on' ? true : false,
            'cyborg'                => $request->cyborg == 'on' ? true : false,
        ]);

        return back()->with('success', 'Settings updated successfully.');
    }
}
