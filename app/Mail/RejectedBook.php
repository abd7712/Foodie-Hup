<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RejectedBook extends Mailable
{
    use Queueable, SerializesModels;

    public $user_name;
    public $date;
    public $start;
    public $end;
    public $rejected_reason;
    public $conflict_books;
    public $conflict_books_buffer_formatted;

    public function __construct($user_name, $date, $start, $end, $rejected_reason, $conflict_books,$conflict_books_buffer_formatted)
    {
        $this->user_name = $user_name;
        $this->date = $date;
        $this->start = $start;
        $this->end = $end;
        $this->rejected_reason = $rejected_reason;
        $this->conflict_books = $conflict_books;
        $this->conflict_books_buffer_formatted = $conflict_books_buffer_formatted;
    }

    public function build()
    {
        return $this->subject('Your Reservation Request Was Rejected')
                    ->view('rejected_book')
                    ->with([
                        'user_name' => $this->user_name,
                        'date' => $this->date,
                        'start' => $this->start,
                        'end' => $this->end,
                        'rejected_reason' => $this->rejected_reason,
                        'conflict_books' => $this->conflict_books,
                        "conflict_books_buffer_formatted"=>$this->conflict_books_buffer_formatted
                    ]);
    }
}
