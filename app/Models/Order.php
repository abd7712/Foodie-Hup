<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
     protected $table = 'orders';

     protected $fillable = [
         'user_id', 
         'book_id', 
         'total_price', 
         'request_invoice',
     ];
 
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function order_items()
    {
        return $this->hasMany(Order_Item::class, 'order_id');
    }

}
