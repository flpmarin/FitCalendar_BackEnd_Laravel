<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coach extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'description',
        'city',
        'country',
        'coach_type',
        'verified',
        'organization_id',
        'payment_info',
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
            'user_id' => 'integer',
            'verified' => 'boolean',
            'organization_id' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the training classes for the coach.
     */
    public function trainingClasses(): HasMany
    {
        return $this->hasMany(TrainingClass::class);
    }
    public function sports()
    {
        return $this->belongsToMany(Sport::class, 'coach_sports')
            ->withPivot('specific_price', 'specific_location', 'session_duration_minutes')
            ->withTimestamps();
    }

    /**
     * Disponibilidades puntuales (por fecha) de este coach.
     */
    public function specificAvailabilities(): HasMany
    {
        return $this->hasMany(SpecificAvailability::class);
    }

}
