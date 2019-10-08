<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ArcherContactAdmin extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($event, $entryurl, $userfrom, $usermessage)
    {
        $this->eventname = $event->label;
        $this->entryurl = $entryurl;
        $this->userfrom = $userfrom;
        $this->usermessage = $usermessage;

    }

    /**
     * Build the usermessage.
     *
     * @return $this
     */
    public function build()
    {

        return $this->view('emails.admincontactadmin')
            ->subject('Archer Entry Update Request')
            ->with([
                'userfrom'  => $this->userfrom,
                'eventname' => $this->eventname,
                'usermessage'   => $this->usermessage,
                'entryurl'  => $this->entryurl
            ]);
    }
}
