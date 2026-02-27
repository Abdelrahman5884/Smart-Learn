<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class SendOtpMail extends Mailable
{
    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('Your Password Reset OTP')
            ->html("
                <h2>Password Reset OTP</h2>
                <p>Your OTP code is:</p>
                <h1>{$this->otp}</h1>
                <p>This code expires in 10 minutes.</p>
            ");
    }
}
