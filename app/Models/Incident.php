<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $primaryKey = 'incident_id';

    protected $fillable = [
        'user_id',
        'incident_type',
        'type',
        'snake_name',
        'location',
        'location_name',
        'lat',
        'lng',
        'image_path',
        'description',
        'status',
        'priority',
        'confidence_level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function rescueRequests()
    {
        return $this->hasMany(RescueRequest::class, 'incident_id', 'incident_id');
    }
}