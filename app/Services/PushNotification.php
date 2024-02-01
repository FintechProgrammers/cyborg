<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class PushNotification
{
    function sendNotification($data)
    {
        $payload = [
            "registration_ids" => $data['push_tokens'],
            "notification" => [
                "title" => $data['title'],
                "body" => $data['message'],
            ]
        ];

        if (!empty($data['data'])) {
            $payload["data"] =  $data['data'];
        }

        $dataString = json_encode($payload);

        return self::handle($dataString);
    }

    function handle($data)
    {
        try {

            // $headers = [
            //     'Authorization' => 'Bearer' . env('FIREBASE_SERVER_KEY'),
            //     'Content-Type' => 'application/json',
            // ];

            $headers = [
                'Authorization' => 'key=' . env('FIREBASE_SERVER_KEY'),
                'Content-Type' => 'application/json',
            ];

            // $client = new Client([
            //     'base_uri' => 'https://fcm.googleapis.com/v1/projects/delta-signal/messages:send',
            //     'verify' => false,
            // ]);

            $client = new Client([
                'base_uri' => 'https://fcm.googleapis.com/fcm/send',
                'verify' => false,
            ]);

            $response = $client->post('send', [
                'headers' => $headers,
                'body' => $data,
            ]);

            // Process the response
            $statusCode = $response->getStatusCode();
            $responseData = $response->getBody()->getContents();

            return json_decode($responseData, true);
        } catch (\Exception $e) {
            sendToLog($e);

            return $e;
        }
    }
}
