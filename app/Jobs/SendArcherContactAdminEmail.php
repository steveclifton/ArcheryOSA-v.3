<?php

namespace App\Jobs;

use App\Mail\ArcherContactAdmin;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendArcherContactAdminEmail extends ArcheryOSASender implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email = null;
    private $event = null;
    private $entryurl = null;
    private $userfrom = null;
    private $usermessage = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($event, $entryurl, $userfrom, $usermessage)
    {
        $this->email = $event->email;
        $this->event = $event;
        $this->entryurl = $entryurl;
        $this->userfrom = $userfrom;
        $this->usermessage = $usermessage;

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
                ->bcc(getenv('MAIL_FROM_ADDRESS'))
                ->send(new ArcherContactAdmin($this->event, $this->entryurl, $this->userfrom, $this->usermessage));
        }
    }
}
