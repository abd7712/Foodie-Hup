<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table="carts";
    protected $fillable=[
        "user_id",
        "product_id",
        "quantity",
        "book_id",
        "note",
        "package_id"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
