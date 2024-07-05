<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\PushNotificationService;

class SendTicketPickupNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $deviceToken;
    public $ticketCount;
    public $personnelName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($deviceToken, $ticketCount, $personnelName)
    {
        $this->deviceToken = $deviceToken;
        $this->ticketCount = $ticketCount;
        $this->personnelName = $personnelName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new PushNotificationService)->sendPushNotification(
            $this->deviceToken,
            "You have " . $this->ticketCount . " unpicked tickets",
            "Please take action, " . $this->personnelName,
            "Ticket Pickup Reminder"
        );
    }
}