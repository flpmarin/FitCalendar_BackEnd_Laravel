<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coach_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coach_id')->constrained()->onDelete('cascade');
            $table->integer('day_of_week'); // 0 (domingo) a 6 (sábado)
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            // Índice para búsquedas rápidas
            $table->index(['coach_id', 'day_of_week', 'is_available']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coach_availabilities');
    }
};
