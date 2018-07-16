<?php

namespace App\Jobs;

use App\Mail\Welcome;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendWelcome extends ArcheryOSASender implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private $firstname;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $firstname)
    {
        $this->email = $email;
        $this->firstname = $firstname;
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
                ->send(new Welcome(ucwords($this->firstname)));
        }

    }
}
