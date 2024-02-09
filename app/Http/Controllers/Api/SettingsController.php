<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SettingsResource;
use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __invoke(Request $request)
    {
        $settings  = Settings::first();

        $settings = new SettingsResource($settings);

        return $this->sendResponse($settings);
    }
}
