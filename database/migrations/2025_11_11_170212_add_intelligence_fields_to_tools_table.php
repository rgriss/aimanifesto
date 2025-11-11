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
        Schema::table('tools', function (Blueprint $table) {
            // Business Intelligence Fields

            // Company name - parent company/organization behind the tool
            $table->string('company_name')->nullable()->after('name');

            // Popularity tier - market recognition level
            $table->enum('popularity_tier', [
                'mainstream',  // Household name (ChatGPT, Photoshop)
                'well_known',  // Known in industry (Cursor, Midjourney)
                'growing',     // Gaining recognition (Bolt, Perplexity)
                'niche',       // Specialized audience (Remove.bg)
                'emerging',    // New/unknown
            ])->nullable()->after('company_name');

            // Momentum score - trajectory assessment (1-5 scale)
            // 1 = Strongly declining, 2 = Declining, 3 = Stable, 4 = Growing, 5 = Rapidly growing
            $table->integer('momentum_score')->nullable()->after('popularity_tier');

            // Index for filtering by popularity
            $table->index('popularity_tier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->dropIndex(['popularity_tier']);
            $table->dropColumn(['company_name', 'popularity_tier', 'momentum_score']);
        });
    }
};
