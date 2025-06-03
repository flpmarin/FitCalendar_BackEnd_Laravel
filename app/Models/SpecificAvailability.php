<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
            'start_time' => 'string',    // HH:MM
            'end_time'   => 'string',
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
}
