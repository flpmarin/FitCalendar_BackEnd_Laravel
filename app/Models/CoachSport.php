<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoachSport extends Model
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
        'specific_price',
        'specific_location',
        'session_duration_minutes',
        'primary',
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
            'specific_price' => 'decimal:2',
            'session_duration_minutes' => 'integer',
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
