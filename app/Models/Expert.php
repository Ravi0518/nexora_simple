<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents a verified snake enthusiast or expert available for rescue assistance.
 */
class Expert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'role',
        'phone',
        'lat',
        'lng',
        'status',
        'rating',
        'total_rescues',
        'profile_image_url',
    ];

    protected $casts = [
        'lat'    => 'float',
        'lng'    => 'float',
        'rating' => 'float',
    ];

    /**
     * Optional link to a user account.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Rescue requests assigned to this expert.
     */
    public function rescueRequests()
    {
        return $this->hasMany(RescueRequest::class, 'expert_id');
    }
}
