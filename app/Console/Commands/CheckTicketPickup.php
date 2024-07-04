<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quote;
use App\Models\Personnel;
use App\Services\PushNotificationService;
use App\Jobs\SendTicketPickupNotification;

class CheckTicketPickup extends Command
{
    protected $signature = 'command:check-ticket-pickup';
    protected $description = 'Check if tickets have been picked up and send notifications';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        info('CheckTicketPickup command started.');


        $tickets = Quote::whereIn('ticket_status', [0,1,2,3])->get();
        foreach ($tickets as $ticket) {
            SendTicketPickupNotification::dispatch($ticket->personnelid, $ticket->id);
            info('Job dispatched for personnel ID: ' . $ticket->personnelid . ' for ticket ID: ' . $ticket->id);
        }

       info('CheckTicketPickup command finished.');
        return 0;
    }
}