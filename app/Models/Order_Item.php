<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_Item extends Model
{
    protected $table="order_items";
    protected $fillable=[
        "order_id",
        "product_id",
        "unit_price",
        "quantity",
        "total_price",
        "status"
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
