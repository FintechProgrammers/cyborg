<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function sendResponse($data, $message = null, $status = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, $status);
    }

    public function sendError($message, $errorData = [], $status = 400)
    {
        $response = [
            'success' => false,
            'message' => $message
        ];

        if (!empty($errorData)) {
            $response['errors'] = $errorData;
        }

        return response()->json($response, $status);
    }

    public function resourceNotFoundResponse(string $resource)
    {
        $response = [
            'error' => "The $resource wasn't found",
        ];

        return response()->json($response, 404);
    }
}
