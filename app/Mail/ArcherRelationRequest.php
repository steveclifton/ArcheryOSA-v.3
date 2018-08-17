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
    private $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($firstname, $requestusername, $hash, $url)
    {
        $this->firstname = ucwords($firstname);
        $this->requestusername = ucwords($requestusername);
        $this->hash = $hash;
        $this->url = $url;
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
                'firstname'       => $this->firstname,
                'requestusername' => $this->requestusername,
                'hash'            => $this->hash,
                'url'             => $this->url
            ]);
    }

}
