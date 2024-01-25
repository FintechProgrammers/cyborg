<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class CoinpaymentController extends Controller
{
    public function __invoke(Request $req)
    {

        $data = trim(file_get_contents('php://input'), "\xEF\xBB\xBF");
        logger($data);

        if (!empty($data)) {
            $decoded = json_decode(mb_convert_encoding($data, 'UTF-8', 'UTF-8'), true, 512, JSON_THROW_ON_ERROR);
        }
    }
}
