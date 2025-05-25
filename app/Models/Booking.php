<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

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
        'payment_status',
        'cancelled_at',
        'cancelled_reason',
    ];

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
            'payment_status' => 'string',
            'cancelled_at' => 'timestamp',
        ];
    }

    // para marcar como pagado
    public function markAsPaid(): self
    {
        $this->update(['payment_status' => 'Completado']);
        return $this;
    }

    //   verificar si está pagado
    public function isPaid(): bool
    {
        return $this->payment_status === 'Completado';
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class);
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

    public function class(): BelongsTo
    {
        return $this->belongsTo(TrainingClass::class, 'class_id');
    }
}
