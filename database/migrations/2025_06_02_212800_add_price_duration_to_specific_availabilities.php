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
        Schema::table('specific_availabilities', function (Blueprint $table) {
            $table->unsignedSmallInteger('duration_minutes')->default(60);
            $table->decimal('price_per_person', 8, 2)->default(0);
            $table->enum('status', ['Available', 'Booked', 'Cancelled'])->default('Available');
        });
    }

    public function down(): void
    {
        Schema::table('specific_availabilities', function (Blueprint $table) {
            $table->dropColumn(['duration_minutes', 'price_per_person', 'status']);
        });
    }

};
