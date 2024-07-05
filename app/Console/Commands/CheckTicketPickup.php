<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quote;
use App\Models\Personnel;
use App\Services\PushNotificationService;
use App\Jobs\SendTicketPickupNotification;
use Illuminate\Support\Facades\DB;

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


        $tickets = Quote::where('ticket_status', 2)
            ->select('personnelid', DB::raw('COUNT(*) as ticket_count'))
            ->groupBy('personnelid')
            ->get();

        foreach ($tickets as $ticket) {
            $personnel = Personnel::find($ticket->personnelid);
            if ($personnel && $personnel->device_token) {
                SendTicketPickupNotification::dispatch($personnel->device_token, $ticket->ticket_count, $personnel->personnelname);
               info('Notification dispatched to personnel ID: ' . $ticket->personnelid . ' with ' . $ticket->ticket_count . ' unpicked tickets.');
            }
        }

        info('CheckTicketPickup command finished.');
        return 0;
    }
}