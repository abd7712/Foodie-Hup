<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user_name;
    public $verificationUrl;

    public function __construct($user_name,$verificationUrl)
    {
        $this->user_name=$user_name;
        $this->verificationUrl=$verificationUrl;
    }

    public function build()
    {
        return $this->subject("Welcome to Foodie Hub 🍽️")->view("WelcomeMail")->with([
            "user_name"=>$this->user_name,
            "verificationUrl"=>$this->verificationUrl
        ]);
    }
    
}
