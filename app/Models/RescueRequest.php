<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents a rescue request linking an incident to an expert.
 */
class RescueRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_id',
        'expert_id',
        'status',
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class, 'incident_id', 'incident_id');
    }

    public function expert()
    {
        return $this->belongsTo(Expert::class, 'expert_id', 'id');
    }
}
