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

        Schema::create('coaches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->text('description')->nullable();
            $table->string('city')->nullable()->index();
            $table->string('country')->nullable()->index();
            $table->enum('coach_type', ['Individual', 'Club'])->default('Individual');
            $table->boolean('verified')->default(false);
            $table->foreignId('organization_id')->nullable()->constrained();
            $table->string('payment_info')->nullable();
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
        Schema::dropIfExists('coaches');
    }
};
