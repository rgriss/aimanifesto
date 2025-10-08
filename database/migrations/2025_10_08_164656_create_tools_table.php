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
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            
            // Basic Info
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('long_description')->nullable();
            
            // Links & Resources
            $table->string('website_url');
            $table->string('documentation_url')->nullable();
            $table->string('logo_url')->nullable();
            
            // Pricing
            $table->enum('pricing_model', ['free', 'freemium', 'paid', 'enterprise'])->default('freemium');
            $table->string('price_description')->nullable(); // e.g., "$20/month" or "Free tier available"
            
            // Ryan's Personal Rating
            $table->integer('ryan_rating')->nullable(); // 1-10 scale
            $table->text('ryan_notes')->nullable(); // Your personal commentary
            $table->date('ryan_last_used')->nullable();
            
            // Technical Details
            $table->json('features')->nullable(); // Array of key features
            $table->json('use_cases')->nullable(); // Array of practical use cases
            $table->json('integrations')->nullable(); // What it integrates with
            
            // Metadata
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('views_count')->default(0);
            $table->date('first_reviewed_at')->nullable();
            
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index('category_id');
            $table->index('is_featured');
            $table->index('ryan_rating');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};
