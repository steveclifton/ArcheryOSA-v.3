<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    private $hash;
    private $email;
    private $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($hash, $email, $name)
    {
        $this->hash = $hash;
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.ResetPassword')
            ->with([
                'hash' => $this->hash,
                'name' => $this->name,
                'email' => $this->email
            ]);
    }
}
