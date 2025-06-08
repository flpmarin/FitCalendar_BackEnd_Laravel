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

    // Accessors para start_time y end_time, que convierten el valor de la base de datos a un objeto Carbon
    public function getStartTimeAttribute($value)
    {
        return $this->parseTime($value)?->format('H:i:s');
    }

    public function getEndTimeAttribute($value)
    {
        return $this->parseTime($value)?->format('H:i:s');
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
