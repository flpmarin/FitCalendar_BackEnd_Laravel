<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('specific_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coach_id')->constrained()->onDelete('cascade');
            $table->foreignId('sport_id')->constrained();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedSmallInteger('capacity')->nullable()->default(1);
            $table->boolean('is_online')->default(false);
            $table->string('location')->nullable();
            $table->boolean('is_booked')->default(false);

            $table->timestamps();

            $table->unique(['coach_id', 'sport_id', 'date', 'start_time', 'end_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specific_availabilities');
    }
};
