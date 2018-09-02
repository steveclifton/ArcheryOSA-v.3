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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $eventname, $entryname)
    {
        $this->email     = $email;
        $this->eventname = $eventname;
        $this->entryname = $entryname;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->checkEmailAddress($this->email)) {
            Mail::to($this->email)
                ->send(new EventAdminEntryReceived(ucwords($this->eventname), $this->entryname));
        }
    }
}
