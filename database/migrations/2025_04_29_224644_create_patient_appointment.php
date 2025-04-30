<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('patient_appointment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')
                  ->constrained('patient')
                  ->onDelete('cascade');
            $table->time('appointment_time');
            $table->enum('status', ['Pendente', 'Confirmado', 'A Confirmar' , 'Cancelado', 'Finalizado', 'Não Compareceu']);
            $table->date('appointment_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_appointment');
    }
};