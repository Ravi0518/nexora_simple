<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Represents the SNAKE entity — stores species data for the mobile app.
 */
class Snake extends Model
{
    use HasFactory;

    protected $primaryKey = 'snake_id';

    protected $fillable = [
        // Core identifiers
        'slug',
        'common_name',       // legacy English name field
        'name',              // English common name (new canonical field)
        'name_si',
        'name_ta',
        'scientific_name',

        // Venom
        'venomous_status',
        'is_venomous',
        'danger_level',
        'danger_level_si',
        'danger_level_ta',

        // Location
        'region',
        'image_url',         // legacy single image field

        // About / Description (EN: 'description' legacy, 'about' new)
        'description',
        'about',
        'about_si',
        'about_ta',

        // Habitat
        'habitat',
        'habitat_si',
        'habitat_ta',

        // Behavior
        'behavior',
        'behavior_si',
        'behavior_ta',

        // Diet
        'diet',
        'diet_si',
        'diet_ta',

        // First Aid (JSON arrays)
        'first_aid_steps',   // legacy plain text
        'first_aid',         // JSON array EN
        'first_aid_si',
        'first_aid_ta',

        // Do NOTs (JSON arrays)
        'donts',
        'donts_si',
        'donts_ta',

        // Misc
        'warning_signs',
        'similar_species',   // JSON array
        'distribution',      // JSON array (legacy)
    ];

    protected $casts = [
        'names'          => 'array',
        'distribution'   => 'array',
        'similar_species'=> 'array',
        'is_venomous'    => 'boolean',
    ];

    /**
     * Auto-generate slug from English name when creating.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($snake) {
            if (empty($snake->slug)) {
                $base = $snake->name ?? $snake->common_name ?? 'snake';
                $snake->slug = Str::slug($base, '_');
            }
        });
    }

    /**
     * Gallery images for this snake (sorted by sort_order).
     */
    public function images()
    {
        return $this->hasMany(SnakeImage::class, 'snake_id', 'snake_id')
                    ->orderBy('sort_order');
    }
}