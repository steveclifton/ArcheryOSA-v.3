<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ArcherRelationConfirm extends Mailable
{
    use Queueable, SerializesModels;

    private $firstname;
    private $relationfirstname;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($firstname, $relationfirstname)
    {
        $this->firstname = ucwords($firstname);
        $this->relationfirstname = ucwords($relationfirstname);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.confirmationarcherrelationship')
            ->subject('Archer Relation Confirmed')
            ->with([
                'firstname' => $this->firstname,
                'relationfirstname' => $this->relationfirstname,
            ]);
    }
}
