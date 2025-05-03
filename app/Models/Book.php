<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table="books";
    protected $fillable = [
        'user_id',
        'table_id',
        'number_of_sets',
        'date',
        'start',
        'end',
        'status',
        "location"
    ];

    public function carts()
    {
        return $this->hasMany(User::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasOne(Order::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function rate()
    {
        return $this->hasOne(Rate::class);
    }
}
