<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OTPMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        // Ensure explicit From header to match MAIL_FROM in .env
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('Your Nexora Verification Code')
                    ->view('emails.otp')
                    ->with(['otp' => $this->otp]);
    }
}