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

        Schema::create('training_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coach_id')->constrained();
            $table->foreignId('sport_id')->constrained();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestampTz('starts_at')->index();
            $table->unsignedSmallInteger('duration_minutes');
            $table->string('location_detail')->nullable();
            $table->boolean('is_online')->default(false);
            $table->decimal('price_per_person', 10, 2);
            $table->unsignedSmallInteger('max_capacity');
            $table->unsignedSmallInteger('min_required')->default(1);
            $table->timestampTz('enrollment_deadline')->nullable();
            $table->enum('status', ["Scheduled","ReadyToConfirm","Confirmed","Cancelled","Completed"])->default('Scheduled');
            $table->timestampTz('cancelled_at')->nullable();
            $table->string('cancelled_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_classes');
    }
};
