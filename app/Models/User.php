<?php

namespace App\Models;

// CORRECT: Import the interface from the Laravel Core
use Illuminate\Contracts\Auth\MustVerifyEmail; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Incident;
use App\Models\Request as AssistanceRequest; // added import and alias

/**
 * Professional English: User Model representing the 'USER' Strong Entity.
 * Implements MustVerifyEmail to handle OTP-based registration flow.
 */
class User extends Authenticatable implements MustVerifyEmail
{
    // FIX: Only list traits once here to avoid "already in use" error
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id'; 

    // Use integer incrementing primary key (adjust if your user_id is non-incrementing/UUID)
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'fname',
        'lname',
        'email',
        'password',
        'phone',
        'role',
        'profile_pic',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relationship: A User (General Public) can report multiple Incidents.
     */
    public function incidents()
    {
        return $this->hasMany(Incident::class, 'user_id', 'user_id');
    }

    /**
     * Relationship: A User can post multiple Assistance Requests.
     */
    public function requests()
    {
        return $this->hasMany(AssistanceRequest::class, 'user_id', 'user_id');
    }
}