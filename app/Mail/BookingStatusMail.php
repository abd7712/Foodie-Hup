<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user_name;
    public $date;
    public $start;
    public $end;
    public $book_status;
    public $custom_message;

    public function __construct($user_name,$date,$start,$end,$book_status,$custom_message)
    {
        $this->user_name=$user_name;
        $this->date=$date;
        $this->start=$start;
        $this->end=$end;
        $this->book_status=$book_status;
        $this->custom_message=$custom_message;
    }

    public function build()
    {
        return $this->subject("Your Table Booking Status – Updated!")->view("booking")->with([
            "user_name"=>$this->user_name,
            "date"=>$this->date,
            "start"=>$this->start,
            "end"=>$this->end,
            "book_Status"=>$this->book_status,
            "message"=>$this->custom_message
        ]);
    }
}
