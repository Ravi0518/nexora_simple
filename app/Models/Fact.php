<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A random "Did You Know?" snake fact shown on the home screen.
 */
class Fact extends Model
{
    use HasFactory;

    protected $fillable = [
        'fact',
        'image_url',
    ];
}
