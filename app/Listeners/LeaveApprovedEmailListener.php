<?php

namespace App\Listeners;

use App\Events\LeaveApprovedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\LeaveApproved;

class LeaveApprovedEmailListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LeaveApprovedEvent $event): void
    {
        Mail::to($event->receiver_email)->send(new LeaveApproved($event->receiver_name, $event->sender_name));
    }
}
