<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tool extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'long_description',
        'website_url',
        'documentation_url',
        'logo_url',
        'screenshot_url',
        'pricing_model',
        'price_description',
        'company_name',
        'popularity_tier',
        'momentum_score',
        'ryan_rating',
        'ryan_notes',
        'ryan_last_used',
        'features',
        'use_cases',
        'integrations',
        'is_featured',
        'is_active',
        'views_count',
        'upvotes',
        'downvotes',
        'first_reviewed_at',
    ];

    protected $casts = [
        'features' => 'array',
        'use_cases' => 'array',
        'integrations' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'views_count' => 'integer',
        'upvotes' => 'integer',
        'downvotes' => 'integer',
        'ryan_rating' => 'integer',
        'momentum_score' => 'integer',
        'ryan_last_used' => 'date',
        'first_reviewed_at' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the category this tool belongs to
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the intelligence data for this tool
     */
    public function intelligence(): HasOne
    {
        return $this->hasOne(ToolIntelligence::class);
    }

    /**
     * Scope to only active tools
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to only featured tools
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to order by Ryan's rating (highest first, unrated last)
     */
    public function scopeHighestRated($query)
    {
        return $query->orderByDesc('ryan_rating')
                    ->orderBy('name');
    }

    /**
     * Scope to filter by category
     */
    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Get the route key name for Laravel
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Increment the views count
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}