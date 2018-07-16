<?php

namespace App\Jobs;

use App\Mail\EntryConfirmation;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendEntryReceived extends ArcheryOSASender implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private $eventname;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $eventname)
    {
        $this->email = $email;
        $this->eventname = $eventname;
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
                ->send(new EntryConfirmation(ucwords($this->eventname)));
        }
    }
}
