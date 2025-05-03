<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create("products",function(Blueprint $table){
            $table->id();
            $table->foreignId("category_id")->constrained("categories")->onDelete("cascade");
            $table->string("name");
            $table->integer("price");
            $table->boolean("isAvailable");
            $table->text("description");
            $table->string("image");
            $table->timestamps();
        });
    }

};
