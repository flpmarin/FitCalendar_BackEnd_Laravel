<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingClass extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'coach_id',
        'sport_id',
        'title',
        'description',
        'starts_at',
        'duration_minutes',
        'location_detail',
        'is_online',
        'price_per_person',
        'max_capacity',
        'min_required',
        'enrollment_deadline',
        'status',
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
            'coach_id' => 'integer',
            'sport_id' => 'integer',
            'starts_at' => 'timestamp',
            'duration_minutes' => 'integer',
            'is_online' => 'boolean',
            'price_per_person' => 'decimal:2',
            'max_capacity' => 'integer',
            'min_required' => 'integer',
            'enrollment_deadline' => 'timestamp',
            'cancelled_at' => 'timestamp',
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
