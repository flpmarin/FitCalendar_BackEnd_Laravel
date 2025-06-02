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

        Schema::create('specific_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coach_id')->constrained();
            $table->foreignId('sport_id')->nullable()->constrained();
            $table->timestampTz('start_at');
            $table->timestampTz('end_at');
            $table->enum('availability_type', ['Available', 'Blocked'])->default('Available');
            $table->unsignedSmallInteger('capacity')->nullable();
            $table->boolean('is_online')->nullable();
            $table->string('location')->nullable();
            $table->string('reason')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specific_availabilities');
    }
};
