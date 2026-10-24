<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\EmailHelpers;

class ProcessSendingEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $name;
    private $pass;
    private $children;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($name, $pass, $children)
    {
        $this->name     = $name;
        $this->pass     = $pass;
        $this->children = $children;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new EmailHelpers();
        foreach ($this->children as $child){
            $email->sendToEmail($this->name, $this->pass, $child->email);
        }
    }

}
