<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create("books",function(Blueprint $table){
                $table->id();
                $table->foreignId("user_id")->constrained("users")->onDelete("cascade");
                $table->foreignId('table_id')->nullable()->constrained('tables')->onDelete('set null');
                $table->integer("number_of_sets");
                $table->string("location");
                $table->date("date");
                $table->time("start");
                $table->time("end");
                $table->string("status");
                $table->timestamps(); 
        });
    }

  
};
