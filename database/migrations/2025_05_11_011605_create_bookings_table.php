
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('coach_id')->nullable()->constrained();
            $table->foreignId('availability_slot_id')->nullable()->constrained();
            $table->foreignId('specific_availability_id')->nullable()->constrained();
            $table->foreignId('class_id')->nullable()->constrained('training_classes');
            $table->enum('type', ["Personal","Group"]);
            $table->timestampTz('session_at')->index();
            $table->unsignedSmallInteger('session_duration_minutes');
            $table->enum('status', ["Pending","Confirmed","Cancelled","Completed","Rejected"])->default('Pending');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('platform_fee', 10, 2);
            $table->string('currency')->default('EUR');
            $table->timestampTz('cancelled_at')->nullable();
            $table->string('cancelled_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
