<?php

namespace App\Jobs;

use App\Mail\ExceptionAlertEmail;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendExceptionEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $exception;
    protected $subject;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($exception, $subject)
    {
        $this->exception = $exception;
        $this->subject = $subject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::where('userid', 1)->first();
        return;
        Mail::to($this->getEmailAddress($user->email))
            ->send(new ExceptionAlertEmail($this->exception, $this->subject));
    }
}
