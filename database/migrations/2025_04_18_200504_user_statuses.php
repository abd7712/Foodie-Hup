<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create("user_statuses",function(Blueprint $table){
            $table->id();
            $table->foreignId("user_id")->constrained("users")->onDelete("cascade");
            $table->string("account_status");
            $table->dateTime("last_login");
            $table->dateTime("last_activity"); 
            $table->string("status");
            $table->timestamps();
        });
    }

 
};
