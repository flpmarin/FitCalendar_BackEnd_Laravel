<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('availability_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coach_id')->constrained();
            $table->foreignId('sport_id')->nullable()->constrained();
            $table->tinyInteger('weekday')->index();
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_online')->default(false);
            $table->string('location')->nullable();
            $table->unsignedSmallInteger('capacity')->default(1);
            $table->timestamps();

            // restricción única para la combinación de columnas
            $table->unique(['coach_id', 'weekday', 'start_time', 'end_time']);

        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availability_slots');
    }
};
