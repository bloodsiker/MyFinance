<?php

namespace App\Jobs;

use App\Mail\ResetPasswordNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessNotificationResetPassword implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private $link;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $link)
    {
        $this->email = $email;
        $this->link  = $link;
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle()
    {
        Mail::to($this->email)->send(new ResetPasswordNotification($this->link));
    }
}
