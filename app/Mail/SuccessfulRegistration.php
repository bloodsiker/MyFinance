<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SuccessfulRegistration extends Mailable
{
    use Queueable, SerializesModels;

    private $name;
    private $pass;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $pass)
    {
        $this->name = $name;
        $this->pass = $pass;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name = $this->name;
        $pass = $this->pass;
        return $this->from('office@jammschool.com')
            ->bcc('office@jammschool.com')->view('email.register', compact('name', 'pass'));
    }
}
