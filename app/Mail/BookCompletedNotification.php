<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookCompletedNotification extends Mailable
{
    use Queueable, SerializesModels;
    public  $user_name;
    public $custom_message;
    public function __construct($user_name,$custom_message)
    {
        $this->user_name=$user_name;
        $this->custom_message=$custom_message;
    }

    public function build()
    {
        return $this->subject('Booking Completed')
                    ->view("book_completed")
                    ->with([
                        'user_name' => $this->user_name,
                        'custom_message' => $this->custom_message,
                    ]);
    }
    

}
