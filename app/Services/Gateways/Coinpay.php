<?php

namespace App\Services\Gateways;

use GuzzleHttp\Client;

class Coinpay
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getBalance()
    {
        $params = [
            'all' => 1
        ];

        return $this->handle('balances', $params);
    }

    public function getDepositAddress()
    {
        $params = [
            'currency' => strtoupper('usdt.trc20'),
            'ipn_url'  => config('constants.coinpay.ipn_url')
        ];

        return $this->handle('get_callback_address', $params);
    }

    public function withdrawal($data)
    {
        $apiParams = [
            'amount'        => $data['amount'],
            'address'       => $data['address'],
            'add_tx_fee'    => 0,
            'auto_confirm'  => 1,
            'currency'      => strtoupper('usdt.trc20'),
            'ipn_url'       => config('constants.coinpay.ipn_url')
        ];

        // Replace "your_command" with the actual API command for withdrawal
        return $this->handle('create_withdrawal', $apiParams);
    }

    private function handle($apiCommand, $options = [])
    {
        try {
            $options['key'] = config('constants.coinpay.public_key');
            $options['version'] = 1;
            $options['format'] = 'json';
            $options['cmd'] = $apiCommand;

            // Build the raw POST data for the HMAC signature
            $rawPostData = http_build_query($options, '', '&');

            // Calculate HMAC signature
            $hmac = hash_hmac('sha512', $rawPostData, config('constants.coinpay.private_key'));

            $response = $this->client->post(config('constants.coinpay.base_url'), [
                'headers' => [
                    'HMAC' => $hmac,
                ],
                'form_params' => $options,
            ]);

            // Get the response body as a string
            $responseBody = $response->getBody()->getContents();

            // Process the API response (e.g., convert JSON to an array)
            return json_decode($responseBody, true);
        } catch (\Exception $e) {
            // Handle exceptions (e.g., connection issues, API errors)
            logger($e->getMessage());
        }
    }
}
