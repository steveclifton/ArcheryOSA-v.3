<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ArcherRelationRequest extends Mailable
{
    use Queueable, SerializesModels;

    private $firstname;
    private $requestusername;
    private $hash;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($firstname, $requestusername, $hash)
    {
        $this->firstname = ucwords($firstname);
        $this->requestusername = ucwords($requestusername);
        $this->hash = $hash;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.authorisearcherrelationship')
            ->with([
                'firstname' => $this->firstname,
                'requestusername' => $this->requestusername,
                'hash' => $this->hash
            ]);
    }

}
