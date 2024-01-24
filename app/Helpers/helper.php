<?php

use App\Models\Settings;
use App\Models\UserExchange;
use App\Services\BlackblazeService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

if (!function_exists('generateReference')) { /* Check_for "generateReference" */
    function generateReference()
    {
        $reference = (string) Str::uuid();
        $reference = str_replace('-', '', $reference);

        return $reference;
    }
} /* End_check for "generateReference" */


// create caption
if (!function_exists('createCaption')) {
    function createCaption($content)
    {
        // Remove HTML tags
        $plainText = strip_tags($content);

        // Remove shortcodes (if any)
        $plainText = preg_replace('/\[.*?\]/', '', $plainText);

        // Set the desired maximum length for the caption
        $maxCaptionLength = 50;

        // Create the caption from the content
        $caption = Str::limit($plainText, $maxCaptionLength);

        // If the content exceeds the maximum length, add an ellipsis at the end
        if (strlen($plainText) > $maxCaptionLength) {
            $caption .= '...';
        }

        return $caption;
    }
}

if (!function_exists('settings')) {
    function settings()
    {
        return (object)[
            'storage' => [
                'driver' => 'b2'
            ]
        ];
    }
}

/**
 * Send mail with the specified driver
 *
 * @param string  $driver
 * @param string  $email
 * @param array  $data
 *
 * @return boolean  true|false
 */
if (!function_exists('sendMailByDriver')) { /* Check_for "sendMailByDriver" */
    function sendMailByDriver($driver, $email, $data)
    {
        // Try and send the mail via the selected dirver
        {
            // Try and send the mail via the selected dirver
            try {
                Mail::mailer($driver)->to($email)->send($data);

                return true;
            } catch (\Exception $e) {
                // Log the driver mail error
                logger($driver == 'smtp' ? 'Mailtrap' : 'Mailgun' . ' Failure => ', [
                    'message' => $e->getMessage(),
                ]);

                return false;
            }
        }
    }
}

if (!function_exists('cyborgPlans')) { /* Check_for "plans allowed for cyborg" */
    function cyborgPlans()
    {
        return  ["Delta Digital Plus", "Delta Digital Plus Renewal", "Delta Digital Plus Upgrade", "Delta Digital Pro Renewal", "Delta Digital Pro Upgrade", "Delta Digital Pro"];
    }
}

if (!function_exists('signalPlans')) { /* Check_for "plans allowed for signal" */
    function signalPlans()
    {
        return  ["Delta Digital Plus", "Delta Digital Plus Renewal", "Delta Digital Plus Upgrade", "Delta Digital Pro Renewal", "Delta Digital Pro Upgrade", "Delta Digital Pro", "Delta Digital Standard Renewal"];
    }
}

if (!function_exists('sendToLog')) { /* send to log" */
    function sendToLog($error)
    {
        // Log the exception if it's in a local environment
        if (env('APP_ENV') === 'local') {
            logger($error);
        } else {
            try {
                $logFilesPath = storage_path('logs');
                // // get all log files
                $logFiles = File::glob($logFilesPath . '/*.log');
                // // get latest log
                $latestLogFile = array_pop($logFiles);

                $logFileContent = File::get($latestLogFile);

                $payload = [
                    'text' => $logFileContent
                ];

                $client = new \GuzzleHttp\Client();
                $client->post('https://hooks.slack.com/services/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', [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'json' => $payload,
                ]);
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }
}


if (!function_exists('uploadFile')) { /* send to log" */
    function uploadFile($file, $folder, $driver = "")
    {
        // using config
        if (config('app.env') === 'local') {
            // The environment is local
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path("{$folder}"), $file_name);

            $fileUrl = url("{$folder}/" . $file_name);
        } else {
            if ($driver === "do_spaces") {
                $extension = $file->getClientOriginalExtension(); // Get the file extension (e.g., 'jpg', 'png', 'pdf')
                // Generate a unique filename using a timestamp and a random string
                $uniqueFileName = time() . '_' . uniqid() . '.' . $extension;

                $filePath = "{$folder}/" . $uniqueFileName;

                $path = Storage::disk('do_spaces')->put($filePath, $file, 'public');
                $fileUrl = Storage::disk('do_spaces')->url($path);
            } else {
                $file_name = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("{$folder}"), $file_name);

                $fileUrl = url("{$folder}/" . $file_name);
            }
        }

        return $fileUrl;
    }
}

if (!function_exists('formatDate')) {
    function formatDate($date)
    {
        $user = auth()->user();

        $userTimezone = optional($user)->timezone ?: config('app.timezone');

        // Convert the created_at timestamp to the user's timezone
        $createdAtInUserTimezone = $date->setTimezone($userTimezone);

        // Format the date for display
        $formattedCreatedAt = $createdAtInUserTimezone->format('M j, Y, g:i A');

        return $formattedCreatedAt;
    }
}


if (!function_exists('isBinded')) {
    function isBinded($exchangeId)
    {

        $userExchange = UserExchange::where('user_id', request()->user->id)->where('is_binded', true)->pluck('exchange_id')->toArray();

        if (in_array($exchangeId, $userExchange)) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('exchangeBalance')) {
    function exchangeBalance($exchangeId)
    {
        $userExchange = UserExchange::where('user_id', request()->user->id)->where('exchange_id', $exchangeId)->first();

        if ($userExchange) {
            return number_format($userExchange->spot_balance + $userExchange->future_balance, 2);
        } else {
            return 0.00;
        }
    }
}


if (!function_exists('formatTime')) {
    function formatTime($timestamp)
    {
        // Convert the Unix timestamp to a Carbon instance
        $carbonDate = Carbon::createFromTimestamp($timestamp);

        // Format the Carbon instance as a human-readable date
        $humanReadableDate = $carbonDate->toDateTimeString();

        $humanReadableDate = $carbonDate->format('jS F, Y');

        return $humanReadableDate;
    }
}


if (!function_exists('systemSettings')) {
    function systemSettings()
    {
        return Settings::first();
    }
}

if (!function_exists('formatNumber')) {
    function formatNumber($number)
    {
        if ($number >= 1000000) {
            $formattedNumber = $number / 1000000 . 'M';
        } elseif ($number >= 1000) {
            $formattedNumber = $number / 1000 . 'K';
        } else {
            $formattedNumber = $number;
        }

        return $formattedNumber;
    }
}


if (!function_exists('tradeSettings')) {
    function tradeSettings($stopLoss,$takeProfit,$capital,$firstBuy,$marginLimit,$mRatio,$priceDrop)
    {
        return [
            'stop_loss'         => $stopLoss,
            'take_profit'       => $takeProfit,
            'capital'           => $capital,
            'first_buy'         => $firstBuy,
            'margin_limit'      => $marginLimit,
            'm_ratio'           => $mRatio,
            'price_drop'        => $priceDrop,
        ];
    }
}


if (!function_exists('tradeValues')) {
    function tradeValues($positionAmount=0,$inPosition=false,$buyPosition=false,$sellPosition=false,$marginCalls=0,$floatingLoss=0,$tradePrice=0,$quantity=0,$profit=0,$firstPrice=0,$averagePrice=0)
    {
        return [
            'position_amount'   => $positionAmount,
            'in_position'       => $inPosition,
            'buy_position'      => $buyPosition,
            'sell_position'     => $sellPosition,
            'margin_calls'      => $marginCalls,
            'floating_loss'     => $floatingLoss,
            'trade_price'       => $tradePrice,
            'quantity'          => $quantity,
            'profit'            => $profit,
            'first_price'       => $firstPrice,
            'average_price'     => $averagePrice,
        ];
    }
}
