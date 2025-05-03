<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EndExtentedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user_name;
    public $custom_message;

    public function __construct($user_name,$custom_message)
    {
        $this->user_name=$user_name;
        $this->custom_message=$custom_message;
    }

    public function build()
    {
        return $this->subject('Your Extended Reservation Has Ended')
                    ->view('end_extented_notification')
                    ->with([
                        'user_name' => $this->user_name,
                        'custom_message' => $this->custom_message,
                    ]);
    }

}
