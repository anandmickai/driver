<?php

namespace App\Jobs;

use App\Traits\Communication\PushFcm;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPushNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, PushFcm;

    public $pushData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($pushData)
    {
        $this->pushData = $pushData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $isNewBooking = isset($this->pushData['isBooking'])?? false;
        $this->sendPushRequest(
            $this->pushData['fcmToken'],
            $this->pushData['title'],
            $this->pushData['body'],
            $isNewBooking
        );
    }
}
