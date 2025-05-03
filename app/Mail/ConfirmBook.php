<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmBook extends Mailable
{
    use Queueable, SerializesModels;

    public $user_name;
    public $date;
    public $start;
    public $end;
    public $table_id;
    public $location;
    public $custom_message;

    public function __construct($user_name, $date, $start, $end, $table_id, $location, $custom_message)
    {
        $this->user_name = $user_name;
        $this->date = $date;
        $this->start = $start;
        $this->end = $end;
        $this->table_id = $table_id;
        $this->location = $location;
        $this->custom_message = $custom_message;
    }

    public function build()
    {
        return $this->subject("Your Reservation Has Been Confirmed ✅")
                    ->view('confirm_book')
                    ->with([
                        'user_name' => $this->user_name,
                        'date' => $this->date,
                        'start' => $this->start,
                        'end' => $this->end,
                        'table_id' => $this->table_id,
                        'location' => $this->location,
                        'custom_message' => $this->custom_message,
                    ]);
    }
    
}
