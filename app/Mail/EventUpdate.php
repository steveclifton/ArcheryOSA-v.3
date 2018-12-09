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
    public function __construct($eventname, $emailmessage, $fromname, $fromemail, $filesArr)
    {
        $this->eventname = $eventname;
        $this->emailmessage = $emailmessage;
        $this->fromname = $fromname;
        $this->fromemail = $fromemail;
        $this->filesArr = $filesArr;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('emails.eventupdate')
            ->replyTo($this->fromemail, ($this->fromname ?? ''))
            ->with([
                'eventname' => $this->eventname,
                'emailmessage' => $this->emailmessage
            ]);

        foreach ($this->filesArr as $file) {
            $email->attach(public_path() . $file);
        }

        return $email;
    }
}
