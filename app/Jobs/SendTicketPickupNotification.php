<?php

namespace App\Jobs;

use App\Models\Personnel;
use App\Services\PushNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SendTicketPickupNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $personnelId;
    protected $ticketId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($personnelId, $ticketId)
    {
        $this->personnelId = $personnelId;
        $this->ticketId = $ticketId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $personnel = Personnel::find($this->personnelId);
        if ($personnel && $personnel->device_token) {
            (new PushNotificationService)->sendPushNotification(
                $personnel->device_token,
                "Ticket #" . $this->ticketId . " has not been picked up",
                "Please take action.",
                "Ticket Pickup Reminder"
            );
           info('Notification sent to personnel ID: ' . $this->personnelId . ' for ticket ID: ' . $this->ticketId);
        }
    }
}