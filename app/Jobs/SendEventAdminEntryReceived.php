<?php

namespace App\Jobs;

use App\Mail\EventAdminEntryReceived;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendEventAdminEntryReceived extends ArcheryOSASender implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private $eventname;
    private $entryname;
    private $fullname;
    private $eventurl;

    /**
     * SendEventAdminEntryReceived constructor.
     * @param $email
     * @param $eventname
     * @param $entryname
     * @param string $fullname
     * @param string $eventurl
     */
    public function __construct($email, $eventname, $entryname, $fullname = '', $eventurl = '')
    {
        $this->email     = $email;
        $this->eventname = $eventname;
        $this->entryname = $entryname;
        $this->fullname  = $fullname;
        $this->eventurl  = $eventurl;
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
                ->send(new EventAdminEntryReceived(ucwords($this->eventname), $this->entryname, $this->fullname, $this->eventurl));
        }
    }
}
