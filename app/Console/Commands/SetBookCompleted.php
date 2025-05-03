<?php

namespace App\Console\Commands;

use App\Mail\BookCompletedNotification;
use App\Mail\ExBook;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SetBookCompleted extends Command
{
    
    protected $signature = 'app:set-book-completed';

   
    protected $description = 'Command description';

   
    public function handle()
    {
        $books=Book::where("status","arrive")->get();

        foreach($books as $book)
        {
            $end=Carbon::parse($book->end)->startOfMinute()->format("H:i");
            $now=Carbon::now()->startOfMinute()->format("H:i");
           
            if($end===$now)
            {
                $order=$book->orders()->first();
    
                $order_items=$order->order_items()->whereIn("status",["pending","progress","ready"])->exists();
                if($order_items)
                {
                    $book->update([
                        "status"=>"extended",
                        "end"=>Carbon::parse($book->date.' '.$end)->addMinutes(30)->format("H:i")
                    ]);

                    Mail::to($book->user->email)->send(new ExBook($book->user->name,"Your reservation has ended, but there are dishes you ordered that are not ready, so the end of the reservation will be extended for an additional 30 minutes."));
                }
                
                else{
                    $total = $order->order_items()->sum('total_price');
                    $order->update([
                        "request_invoice"=>true,
                        "total_price"=>$total
                    ]);
                    $book->update([
                        "status"=>"completed"
                    ]);

                    Mail::to($book->user->email)->send(new BookCompletedNotification($book->user->name,"Your booking has been completed. You can now view your invoice from your account."));
                }
            }
        }
    }
}
