<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tool_intelligence', function (Blueprint $table) {
            $table->id();

            // Foreign key to tools table (one-to-one relationship)
            $table->foreignId('tool_id')
                ->unique()
                ->constrained('tools')
                ->cascadeOnDelete();

            // === COMPANY METADATA ===
            $table->integer('founded_year')->nullable();
            $table->integer('tool_launched_year')->nullable();
            $table->enum('company_status', [
                'private',
                'public',
                'acquired',
                'subsidiary',
                'open_source',
            ])->nullable();
            $table->string('stock_ticker')->nullable();
            $table->string('parent_company')->nullable();
            $table->string('acquisition_date')->nullable();
            $table->string('headquarters')->nullable();
            $table->enum('employee_count_range', [
                '1-10',
                '11-50',
                '51-200',
                '201-500',
                '501-1000',
                '1000-5000',
                '5000-10000',
                '10000+',
            ])->nullable();

            // === MARKET POSITION ===
            $table->enum('estimated_users', [
                '< 10K',
                '10K-100K',
                '100K-1M',
                '1M-10M',
                '10M-50M',
                '50M-100M',
                '100M+',
            ])->nullable();
            $table->json('target_market')->nullable(); // ["individual", "small_business", etc.]
            $table->enum('market_position', [
                'market_leader',
                'major_player',
                'challenger',
                'niche_specialist',
                'emerging',
            ])->nullable();
            $table->json('primary_competitors')->nullable(); // Array of strings

            // === MOMENTUM & SENTIMENT ===
            $table->text('momentum_notes')->nullable();
            $table->enum('customer_sentiment', [
                'very_positive',
                'positive',
                'mixed',
                'negative',
                'very_negative',
            ])->nullable();
            $table->text('sentiment_notes')->nullable();
            $table->string('last_major_update')->nullable();

            // === FINANCIAL ===
            $table->enum('funding_stage', [
                'bootstrapped',
                'seed',
                'series_a',
                'series_b',
                'series_c+',
                'public',
                'profitable',
                'acquired',
            ])->nullable();
            $table->string('latest_funding_amount')->nullable();
            $table->string('latest_funding_date')->nullable();
            $table->enum('estimated_annual_revenue', [
                '< $1M',
                '$1M-$10M',
                '$10M-$50M',
                '$50M-$100M',
                '$100M-$500M',
                '$500M-$1B',
                '$1B+',
            ])->nullable();

            // === COMPETITIVE INTELLIGENCE ===
            $table->json('key_differentiators')->nullable(); // Array of strings
            $table->json('strengths')->nullable(); // Array of strings
            $table->json('weaknesses')->nullable(); // Array of strings
            $table->text('market_threats')->nullable();
            $table->text('growth_opportunities')->nullable();

            // === ANALYST NOTES ===
            $table->text('strategic_notes')->nullable();
            $table->text('analyst_summary')->nullable();

            // === METADATA ===
            $table->integer('data_completeness_score')->default(0);
            $table->timestamp('last_researched_at')->nullable();

            $table->timestamps();

            // Indexes for performance
            $table->index('tool_id');
            $table->index('data_completeness_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tool_intelligence');
    }
};
