<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EntryConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $eventname;
    public $firstname;
    public $eventurl;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($eventname, $firstname, $eventurl)
    {
        $this->eventname = $eventname;
        $this->firstname = $firstname;
        $this->eventurl  = $eventurl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->eventname . ' Entry Confirmed!')
            ->view('emails.entryconfirmed')
            ->with([
                'eventname' => $this->eventname,
                'firstname' => $this->firstname,
                'eventurl' => $this->eventurl
            ]);
    }
}
