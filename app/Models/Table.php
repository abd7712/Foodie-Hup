<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $table="tables";
    protected $fillable=[
        "number_of_sets",
        "status",
        "location"
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
