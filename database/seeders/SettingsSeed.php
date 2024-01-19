<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Settings::create([
            'withdrawal_status'     => \App\Models\Settings::STATUS['Disable'],
            'automatic_withdrawal'  => \App\Models\Settings::STATUS['Disable'],
            'minimum_widthdrawal'   => 0.00,
            'maximum_widthdrawal'   => 0.00,
            'withdrawal_fee'        => 0.00,
            'trade_status'          => \App\Models\Settings::STATUS['Disable'],
            'trade_fee'             => 0.00
        ]);
    }
}
