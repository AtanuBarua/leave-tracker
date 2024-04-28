<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeaveRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $approvedByName;


    /**
     * Create a new message instance.
     */
    public function __construct($userName, $approvedByName)
    {
        $this->userName = $userName;
        $this->approvedByName = $approvedByName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Leave Request Rejected',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.leave.rejected',
            with: [
                'user_name' => $this->userName,
                'approved_by_name' => $this->approvedByName,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
