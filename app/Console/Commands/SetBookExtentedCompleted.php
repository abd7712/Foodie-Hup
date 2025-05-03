<?php

namespace App\Console\Commands;

use App\Mail\EndExtentedNotification;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SetBookExtentedCompleted extends Command
{
    
    protected $signature = 'app:set-book-extented-completed';
 
    protected $description = 'Command description';

    public function handle()
    {
        $books=Book::where("status","extended")->get();

        foreach($books as $book)
        {
            $end=Carbon::parse($book->end)->startOfMinute()->format("H:i");
            $now=Carbon::now()->startOfMinute()->format("H:i");
            if($end===$now)
            {
                $order=$book->orders()->first();

                $order_items=$order->order_items()->whereIn("status",["pending","progress","ready"])->exists();
                if(!$order_items)
                {
                    echo "hi";
                    $total = $order->order_items()->sum('total_price');
                    $order->update([
                        "request_invoice"=>true,
                        "total_price"=>$total
                    ]);
                    $book->update([
                        "status"=>"completed"
                    ]);

                    Mail::to($book->user->email)->send(new EndExtentedNotification($book->user->name,"Your extended reservation has ended. You can see your invoice. We apologize for the delay. Thank you for your visit."));
                }

            }
        }
    }
}
