<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use carbon\carbon;

class SpecificAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_id',
        'sport_id',
        'date',
        'start_time',
        'end_time',
        'is_online',
        'location',
        'is_booked',
    ];

    protected function casts(): array
    {
        return [
            'id'         => 'integer',
            'coach_id'   => 'integer',
            'sport_id'   => 'integer',
            'date'       => 'date',            // YYYY-MM-DD
            'is_online'  => 'boolean',
            'is_booked'  => 'boolean',
        ];
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(Coach::class);
    }

    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }
    // Accessor para start_time, que convierte el valor de la base de datos a un objeto Carbon
    public function getStartTimeAttribute($value)
    {
        return Carbon::createFromFormat('H:i', $value);
    }

    // Accessor para end_time, que convierte el valor de la base de datos a un objeto Carbon
    public function getEndTimeAttribute($value)
    {
        return Carbon::createFromFormat('H:i', $value);
    }
}
