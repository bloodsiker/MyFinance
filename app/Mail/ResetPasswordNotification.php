<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordNotification extends Mailable
{
    use Queueable, SerializesModels;

    private $link;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($link)
    {
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $link = $this->link;
        return $this
            ->subject("Відновлення паролю від особистого кабінету JAMM school")
            ->bcc('office@jammschool.com')
            ->from('office@jammschool.com')
            ->view('email.reset_password', compact('link'));
    }
}
