<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    function banners()
    {
        $banners = Banner::where('enabled',true)->get();

        return $this->sendResponse(['banners'=>$banners],"List of Banners");
    }
}
