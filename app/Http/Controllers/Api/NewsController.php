<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends ApiController
{
    function index()
    {
        $news = NewsResource::collection(News::where('status','published')->get());

        return $this->sendResponse($news,"List of news.");
    }

    function show(News $news)
    {
        $news = new NewsResource($news);

        return $this->sendResponse($news,"News details.");
    }
}
