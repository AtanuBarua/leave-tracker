<?php

namespace App\Listeners;

use App\Events\LeaveRejectedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\LeaveRejected;

class LeaveRejectedEmailListener implements ShouldQueue
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
    public function handle(LeaveRejectedEvent $event): void
    {
        Mail::to($event->receiver_email)->send(new LeaveRejected($event->receiver_name, $event->sender_name));
    }
}
