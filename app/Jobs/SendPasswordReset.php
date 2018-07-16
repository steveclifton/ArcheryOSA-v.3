<?php

namespace App\Jobs;

use App\Mail\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendPasswordReset extends ArcheryOSASender implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private $name;
    private $hash;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $name, $hash)
    {
        $this->email = $email;
        $this->name = $name;
        $this->hash = $hash;
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
                ->send(new ResetPassword($this->hash, $this->email, ucwords($this->name)));
        }

    }
}
