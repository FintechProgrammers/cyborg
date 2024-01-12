<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends ApiController
{
    function banners()
    {
        $banners = Banner::get();

        return $this->sendResponse(['banners'=>$banners],"List of Banners");
    }
}
