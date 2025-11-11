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
        Schema::table('tool_intelligence', function (Blueprint $table) {
            // Pricing complexity indicators (1-5 scale, like restaurant dollar signs)
            // Don't specify column position for better compatibility across environments
            $table->unsignedTinyInteger('pricing_individual_cost')->nullable();
            $table->unsignedTinyInteger('pricing_smb_cost')->nullable();
            $table->unsignedTinyInteger('pricing_enterprise_cost')->nullable();
            $table->text('pricing_cost_notes')->nullable();

            // Typical spend ranges for reference
            $table->string('pricing_individual_range')->nullable();
            $table->string('pricing_smb_range')->nullable();
            $table->string('pricing_enterprise_range')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tool_intelligence', function (Blueprint $table) {
            $table->dropColumn([
                'pricing_individual_cost',
                'pricing_smb_cost',
                'pricing_enterprise_cost',
                'pricing_cost_notes',
                'pricing_individual_range',
                'pricing_smb_range',
                'pricing_enterprise_range',
            ]);
        });
    }
};
