<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create("rates",function(Blueprint $table){
            $table->id();
            $table->foreignId("user_id")->constrained("users")->onDelete("cascade");
            $table->foreignId("book_id")->constrained("books")->onDelete("cascade");
            $table->integer("rate");
            $table->timestamps();
        });
    }

};
