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

        Schema::create('coach_sports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coach_id')->constrained();
            $table->foreignId('sport_id')->constrained();
            $table->decimal('specific_price', 10, 2)->nullable();
            $table->string('specific_location')->nullable();
            $table->unsignedSmallInteger('session_duration_minutes')->default(60);
            $table->timestamps();

            //
            $table->unique(['coach_id', 'sport_id']);

        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coach_sports');
    }
};
