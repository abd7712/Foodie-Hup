<?php

namespace App\Console\Commands;

use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookEndNotification;

class BookEndNotificationMail extends Command
{
   
    protected $signature = 'app:book-end-notification-mail';

   
    protected $description = 'Command description';

    
    public function handle()
    {
        $now=Carbon::now()->subMinutes(10);
        $books=Book::with("user")->where("status","arrive")->get();
        foreach($books as $book)
        {
            $end=Carbon::parse($book->end)->subMinutes(10);
            if($now->format("H:i")<=$end->format("H:i"))
            {   
                Mail::to($book->user->email)->send(new BookEndNotification($book->user->name,"Your reservation will expire in 10 minutes. Request the bill now."));
            }
        }
    }
}
