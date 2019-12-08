<?php

namespace App\Jobs;

use App\Mail\EventUpdate;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendEventUpdate extends ArcheryOSASender implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private $eventname;
    private $emailmessage;
    private $fromname;
    private $fromemail;
    private $filesArr;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $eventname, $emailmessage, $fromname, $fromemail, $filesArr = [])
    {
        $this->email = $email;
        $this->eventname = $eventname;
        $this->emailmessage = $emailmessage;
        $this->fromname = $fromname;
        $this->fromemail = $fromemail;
        $this->filesArr = $filesArr;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->checkEmailAddress($this->email)) {
            Mail::to($this->getEmailAddress($this->email))
                ->bcc('info@archeryosa.com')
                ->send(new EventUpdate(ucwords($this->eventname), $this->emailmessage, $this->fromname, $this->fromemail, $this->filesArr));
        }
    }
}
