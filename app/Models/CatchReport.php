<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatchReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_id',
        'enthusiast_id',
        'user_id',
        'caught_lat',
        'caught_lng',
        'snake_image_path',
        'species_identified',
        'snake_condition',
        'enthusiast_comments',
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class, 'incident_id', 'incident_id');
    }

    public function enthusiast()
    {
        return $this->belongsTo(User::class, 'enthusiast_id', 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
