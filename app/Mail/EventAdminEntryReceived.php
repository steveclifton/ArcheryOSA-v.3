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
    public $fullname;
    public $eventurl;

    /**
     * EventAdminEntryReceived constructor.
     * @param $eventname
     * @param $entryname
     * @param string $fullname
     * @param string $eventurl
     */
    public function __construct($eventname, $entryname, $fullname = '', $eventurl = '')
    {
        $this->eventname = $eventname;
        $this->entryname = $entryname;
        $this->fullname = $fullname;
        $this->eventurl = route('manageevent', ['eventurl' => $eventurl]);
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
                'entryname' => $this->entryname,
                'fullname'  => $this->fullname,
                'eventurl'  => $this->eventurl
            ]);
    }
}
