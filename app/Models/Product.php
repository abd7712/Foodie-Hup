<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table="products";
    protected $fillable=[
        "category_id",
        "name",
        "price",
        "isAvailable",
        "description",
        "image"
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function carts()
    {
        return $this->hasMany(User::class);
    }

    public function order_items()
    {
        return $this->hasMany(Order_Item::class);
    }
}
