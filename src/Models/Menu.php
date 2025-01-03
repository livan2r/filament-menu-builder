<?php

namespace Biostate\FilamentMenuBuilder\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Menu extends Model
{
    use HasFactory,
        HasSlug,
        HasTranslations,
        SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'enabled',
    ];

    public array $translatable = [
        'name',
        'description',
    ];

    public $casts = [
        'enabled' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
