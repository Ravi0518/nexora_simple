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
        'assigned_enthusiast_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function rescueRequests()
    {
        return $this->hasMany(RescueRequest::class, 'incident_id', 'incident_id');
    }

    public function assignedEnthusiast()
    {
        return $this->belongsTo(User::class, 'assigned_enthusiast_id', 'user_id');
    }

    public function catchReport()
    {
        return $this->hasOne(CatchReport::class, 'incident_id', 'incident_id');
    }
}