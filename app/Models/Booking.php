<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'coach_id',
        'specific_availability_id',
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

    protected $casts = [
        'student_id' => 'integer',
        'specific_availability_id' => 'integer',
        'session_at' => 'datetime',
        'session_duration_minutes' => 'integer',
        'total_amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'cancelled_at' => 'datetime',
        'payment_status' => 'string',
    ];

    /** Relaciones **/
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function specificAvailability(): BelongsTo
    {
        return $this->belongsTo(SpecificAvailability::class);
    }

    /** Métodos de utilidad **/
    public function markAsPaid(): self
    {
        $this->update(['payment_status' => 'Completado']);
        return $this;
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'Completado';
    }

    /** Relación indirecta (si querés acceder al coach fácil) **/
    public function coach()
    {
        return $this->specificAvailability->coach();
    }
}
