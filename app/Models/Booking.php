<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id',
        'coach_id',
        'availability_slot_id',
        'specific_availability_id',
        'class_id',
        'type',
        'session_at',
        'session_duration_minutes',
        'status',
        'total_amount',
        'platform_fee',
        'currency',
        'cancelled_at',
        'cancelled_reason',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'student_id' => 'integer',
            'coach_id' => 'integer',
            'availability_slot_id' => 'integer',
            'specific_availability_id' => 'integer',
            'class_id' => 'integer',
            'session_at' => 'timestamp',
            'session_duration_minutes' => 'integer',
            'total_amount' => 'decimal:2',
            'platform_fee' => 'decimal:2',
            'cancelled_at' => 'timestamp',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(Coach::class);
    }

    public function availabilitySlot(): BelongsTo
    {
        return $this->belongsTo(AvailabilitySlot::class);
    }

    public function specificAvailability(): BelongsTo
    {
        return $this->belongsTo(SpecificAvailability::class);
    }

    public function trainingClass(): BelongsTo
    {
        return $this->belongsTo(TrainingClass::class, 'class_id');
    }
}
