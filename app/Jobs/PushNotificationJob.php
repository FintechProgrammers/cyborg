<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $data)
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $firebase = new \App\Services\PushNotification();

            $firebase->sendNotification($this->data);
        } catch (\Exception $e) {
            sendToLog(['push notification' => $e]);
        }
    }
}
