<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoachSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_id',
        'date',
        'time',
        'sport',
        'price',
        'location',
    ];

    // Relaciones si es necesario
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
}