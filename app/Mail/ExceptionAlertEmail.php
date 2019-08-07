<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExceptionAlertEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $exception;
    public $subject;

    /**
     * Create a new message instance.
     * ExceptionAlertEmail constructor.
     * @param $exception
     * @param $subject
     */
    public function __construct($exception, $subject)
    {
        $this->exception = getenv('APP_URL') . '<br>' . $exception;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.exception')
            ->subject($this->subject)
            ->with([
                'exception' => $this->exception,
            ]);
    }
}
