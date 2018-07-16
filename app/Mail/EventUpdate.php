<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventUpdate extends Mailable
{
    use Queueable, SerializesModels;

    private $eventname;
    private $emailmessage;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($eventname, $emailmessage)
    {
        $this->eventname = $eventname;
        $this->emailmessage = $emailmessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.eventupdate')
            ->with([
                'eventname' => $this->eventname,
                'emailmessage' => $this->emailmessage
            ]);
    }
}
