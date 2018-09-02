<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventAdminEntryReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $entryname;
    public $eventname;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($eventname, $entryname)
    {
        $this->eventname = $eventname;
        $this->entryname = $entryname;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.eventadminentry')
            ->subject($this->eventname . " - Entry Received!")
            ->with([
                'eventname' => $this->eventname,
                'entryname' => $this->entryname
            ]);
    }
}
