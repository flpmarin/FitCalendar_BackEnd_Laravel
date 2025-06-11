<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

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
        'capacity',
    ];

    protected function casts(): array
    {
        return [
            'id'         => 'integer',
            'coach_id'   => 'integer',
            'sport_id'   => 'integer',
            'date'       => 'date', //  formato Y-m-d
            'start_time' => 'datetime:H:i:s', // Formato de hora
            'end_time'   => 'datetime:H:i:s', // Formato de hora
            'is_online'  => 'boolean',
            'is_booked'  => 'boolean',
            'capacity'   => 'integer',
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
