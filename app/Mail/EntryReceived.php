<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EntryReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $eventname;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($eventname)
    {
        $this->eventname = $eventname;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.EntryConfirmation')
            ->with([
                'eventname' => $this->eventname
            ]);
    }
}
