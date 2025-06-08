<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class AvailabilitySlot extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'coach_id',
        'sport_id',
        'weekday',
        'is_online',
        'location',
        'capacity',
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
            'coach_id' => 'integer',
            'sport_id' => 'integer',
            'weekday' => 'integer',
            'is_online' => 'boolean',
            'capacity' => 'integer',
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
        return Carbon::createFromFormat('H:i', $value);
    }

    // Accessor para end_time, que convierte el valor de la base de datos a un objeto Carbon
    public function getEndTimeAttribute($value)
    {
        return Carbon::createFromFormat('H:i', $value);
    }

}
