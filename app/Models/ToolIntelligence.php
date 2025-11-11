<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToolIntelligence extends Model
{
    protected $table = 'tool_intelligence';

    protected $fillable = [
        'tool_id',
        // Company Metadata
        'founded_year',
        'tool_launched_year',
        'company_status',
        'stock_ticker',
        'parent_company',
        'acquisition_date',
        'headquarters',
        'employee_count_range',
        // Market Position
        'estimated_users',
        'target_market',
        'market_position',
        'primary_competitors',
        // Momentum & Sentiment
        'momentum_notes',
        'customer_sentiment',
        'sentiment_notes',
        'last_major_update',
        // Financial
        'funding_stage',
        'latest_funding_amount',
        'latest_funding_date',
        'estimated_annual_revenue',
        // Competitive Intelligence
        'key_differentiators',
        'strengths',
        'weaknesses',
        'market_threats',
        'growth_opportunities',
        // Analyst Notes
        'strategic_notes',
        'analyst_summary',
        // Metadata
        'data_completeness_score',
        'last_researched_at',
    ];

    protected $casts = [
        'tool_id' => 'integer',
        'founded_year' => 'integer',
        'tool_launched_year' => 'integer',
        'target_market' => 'array',
        'primary_competitors' => 'array',
        'key_differentiators' => 'array',
        'strengths' => 'array',
        'weaknesses' => 'array',
        'data_completeness_score' => 'integer',
        'last_researched_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the tool this intelligence belongs to
     */
    public function tool(): BelongsTo
    {
        return $this->belongsTo(Tool::class);
    }

    /**
     * Calculate and update the data completeness score
     * Based on percentage of filled fields (excluding id, tool_id, timestamps, and score itself)
     */
    public function calculateCompletenessScore(): int
    {
        // Get all fillable fields except metadata
        $fieldsToCheck = array_filter($this->fillable, function ($field) {
            return !in_array($field, [
                'tool_id',
                'data_completeness_score',
                'last_researched_at',
            ]);
        });

        $totalFields = count($fieldsToCheck);
        $filledFields = 0;

        foreach ($fieldsToCheck as $field) {
            $value = $this->$field;

            // Check if field has meaningful data
            if ($value !== null && $value !== '' && $value !== []) {
                $filledFields++;
            }
        }

        // Calculate percentage and round to integer
        $score = $totalFields > 0 ? round(($filledFields / $totalFields) * 100) : 0;

        return (int) $score;
    }

    /**
     * Update the completeness score
     */
    public function updateCompletenessScore(): void
    {
        $this->data_completeness_score = $this->calculateCompletenessScore();
        $this->saveQuietly(); // Save without triggering events
    }

    /**
     * Boot the model
     */
    protected static function booted(): void
    {
        // Automatically calculate completeness score after save
        static::saved(function (ToolIntelligence $intelligence) {
            if ($intelligence->wasChanged() && !$intelligence->wasChanged('data_completeness_score')) {
                $intelligence->updateCompletenessScore();
            }
        });
    }
}
