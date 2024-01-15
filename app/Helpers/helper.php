<?php

use App\Services\BlackblazeService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $maxCaptionLength = 150;

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
    function sendToLog($log)
    {
        logger($log);
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
