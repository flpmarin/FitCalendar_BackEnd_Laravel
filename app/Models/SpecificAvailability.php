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
            'date'       => 'date',            // YYYY-MM-DD
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

    // Accessors robustos que aceptan H:i o H:i:s
    public function getStartTimeAttribute($value)
    {
        return $this->parseTime($value);
    }

    public function getEndTimeAttribute($value)
    {
        return $this->parseTime($value);
    }

    private function parseTime($value)
    {
        if (!$value) return null;

        try {
            return Carbon::createFromFormat('H:i:s', $value);
        } catch (\Exception $e) {
            try {
                return Carbon::createFromFormat('H:i', $value);
            } catch (\Exception $e) {
                return null;
            }
        }
    }

}
