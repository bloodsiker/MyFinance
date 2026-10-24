<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReferralAfterPayment extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    private $firstPayment;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $firstPayment)
    {
        $this->user = $user;
        $this->firstPayment = $firstPayment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->user;
        $template = $this->firstPayment ? 'email.referral_first_payment' : 'email.referral_each_payment';
        return $this
            ->subject("Програма лояльності JAMM school")
            ->bcc('office@jammschool.com')
            ->from('office@jammschool.com')
            ->view($template, compact('user'));
    }
}
