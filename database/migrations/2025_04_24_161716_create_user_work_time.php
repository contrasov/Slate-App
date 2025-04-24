<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('user_work_time', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained() /* vazio laravel ja entende que é user */
                  ->onDelete('cascade');
            $table->tinyInteger('weekday');   /* armazena dia da semaan [0..6] */
            $table->time('start_time'); 
            $table->time('end_time');
            $table->time('duration');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_work_time');
    }
};
