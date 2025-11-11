<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Tool;
use Illuminate\Console\Command;

class DiagnoseCategory extends Command
{
    protected $signature = 'diagnose:category {slug : Category slug to diagnose}';

    protected $description = 'Diagnose issues with a category and its tools';

    public function handle(): int
    {
        $slug = $this->argument('slug');

        $this->info("🔍 Diagnosing category: {$slug}");
        $this->newLine();

        // Find the category
        $category = Category::where('slug', $slug)->first();

        if (! $category) {
            $this->error("❌ Category with slug '{$slug}' not found!");
            $this->newLine();

            // Show similar categories
            $this->info('Similar categories:');
            $similar = Category::where('slug', 'LIKE', "%{$slug}%")
                ->orWhere('name', 'LIKE', "%{$slug}%")
                ->get();

            if ($similar->isEmpty()) {
                $this->warn('No similar categories found');
            } else {
                foreach ($similar as $cat) {
                    $this->line("  - {$cat->name} (slug: {$cat->slug}, ID: {$cat->id})");
                }
            }

            return self::FAILURE;
        }

        // Category found
        $this->info("✓ Category found:");
        $this->line("  ID: {$category->id}");
        $this->line("  Name: {$category->name}");
        $this->line("  Slug: {$category->slug}");
        $this->line("  Active: ".($category->is_active ? 'Yes' : 'No'));
        $this->newLine();

        // Get all tools in category
        $allTools = Tool::where('category_id', $category->id)->get();
        $activeTools = $allTools->where('is_active', true);

        $this->info("📊 Tool Statistics:");
        $this->line("  Total tools: {$allTools->count()}");
        $this->line("  Active tools: {$activeTools->count()}");
        $this->line("  Inactive tools: ".($allTools->count() - $activeTools->count()));
        $this->newLine();

        if ($allTools->isEmpty()) {
            $this->warn("⚠️  No tools found in this category!");
            $this->newLine();
            $this->info('Possible reasons:');
            $this->line('  1. Tools were added to a different category');
            $this->line('  2. Category ID mismatch');
            $this->line('  3. Tools were deleted');

            return self::SUCCESS;
        }

        // Show all tools
        $this->info('🔧 Tools in this category:');
        $this->newLine();

        foreach ($allTools as $tool) {
            $status = $tool->is_active ? '✓ Active' : '✗ Inactive';
            $featured = $tool->is_featured ? '⭐' : '  ';

            $this->line("{$featured} {$status} | {$tool->name} (slug: {$tool->slug})");
            $this->line("     ID: {$tool->id} | Category ID: {$tool->category_id}");
        }

        $this->newLine();

        // Check for inactive tools
        if ($allTools->count() > $activeTools->count()) {
            $this->warn("⚠️  You have ".($allTools->count() - $activeTools->count()).' inactive tools that won\'t show on the category page!');
            $this->newLine();
            $this->info('To activate all tools in this category, run:');
            $this->line("  php artisan tinker --execute=\"App\\Models\\Tool::where('category_id', {$category->id})->update(['is_active' => true]);\"");
        }

        return self::SUCCESS;
    }
}
