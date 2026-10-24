<?php

namespace App\Helpers;

use App\Mail\NotificationPaymentParent;
use App\Mail\ReferralAfterPayment;
use App\Mail\SuccessfulRegistration;
use Illuminate\Support\Facades\Mail;

class EmailHelpers
{
    public function sendToEmail($name, $pass, $email)
    {
        Mail::to($email)->send(new SuccessfulRegistration($name, $pass));
    }

    public function notificationPaymentParent($email, $text)
    {
        Mail::to($email)->send(new NotificationPaymentParent($text));
    }

    public function sendEmailReferral($user, $firstPayment = false)
    {
        Mail::to($user->email)->send(new ReferralAfterPayment($user, $firstPayment));
    }
}
