<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendSmsJob implements ShouldQueue
{
    use Queueable;

    protected $phoneNumber;
    protected $message;


    /**
     * Create a new job instance.
     */
    public function __construct($phoneNo, $msg)
    {
        //
        $this->phoneNumber = $phoneNo;
        $this->message = $msg;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Logic for sending the SMS message
        // SMSService::sendSms($this->phoneNumber, $this->message);
    }
}
