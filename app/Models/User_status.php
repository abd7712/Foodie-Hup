<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_status extends Model
{
    protected $table="user_statuses";
    protected $fillable=[
        "user_id",
        "account_status",
        "last_login",
        "last_activity"
        ,"status"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
