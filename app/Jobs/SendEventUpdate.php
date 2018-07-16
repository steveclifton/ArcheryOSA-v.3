<?php

namespace App\Jobs;

use App\Mail\EventUpdate;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendEventUpdate extends ArcheryOSASender implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private $eventname;
    private $emailmessage;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $eventname, $emailmessage)
    {
        $this->email = $email;
        $this->eventname = $eventname;
        $this->emailmessage = $emailmessage;

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
                ->send(new EventUpdate(ucwords($this->eventname), $this->emailmessage));
        }
    }
}
