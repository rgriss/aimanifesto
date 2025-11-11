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
            // Add mid-market tier fields (50-500 users) after SMB fields
            $table->unsignedTinyInteger('pricing_midmarket_cost')->nullable()->after('pricing_smb_range');
            $table->string('pricing_midmarket_range')->nullable()->after('pricing_midmarket_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tool_intelligence', function (Blueprint $table) {
            $table->dropColumn(['pricing_midmarket_cost', 'pricing_midmarket_range']);
        });
    }
};
