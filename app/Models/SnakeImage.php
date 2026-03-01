<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents one image in a snake's gallery (up to 3 per snake).
 * sort_order: 0 = hero/main image, 1-2 = additional gallery slides.
 */
class SnakeImage extends Model
{
    protected $fillable = [
        'snake_id',
        'image_url',
        'sort_order',
        'label',
    ];

    public function snake()
    {
        return $this->belongsTo(Snake::class, 'snake_id', 'snake_id');
    }
}
