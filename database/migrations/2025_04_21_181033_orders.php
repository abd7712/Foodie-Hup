<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create("orders",function(Blueprint $table){
            $table->id();
            $table->foreignId("user_id")->constrained("users")->onDelete("cascade");
            $table->foreignId("book_id")->constrained("books")->onDelete("cascade");
            $table->integer("total_price")->nullable();
            $table->boolean("request_invoice")->default(false);
            $table->timestamps();
        });
    }

};
