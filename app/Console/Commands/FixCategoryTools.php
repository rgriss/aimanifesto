<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Tool;
use Illuminate\Console\Command;

class FixCategoryTools extends Command
{
    protected $signature = 'fix:category-tools
                            {--activate-all : Activate all inactive tools}
                            {--move-tools= : Move tools from one category slug to another (format: source-slug:target-slug)}
                            {--merge-categories= : Merge two categories (format: source-slug:target-slug, source will be deleted)}';

    protected $description = 'Fix common category and tool issues';

    public function handle(): int
    {
        $this->info('🔧 Category Tools Fixer');
        $this->newLine();

        // Activate all inactive tools
        if ($this->option('activate-all')) {
            return $this->activateAllTools();
        }

        // Move tools between categories
        if ($moveOption = $this->option('move-tools')) {
            return $this->moveTools($moveOption);
        }

        // Merge categories
        if ($mergeOption = $this->option('merge-categories')) {
            return $this->mergeCategories($mergeOption);
        }

        $this->warn('No action specified. Use one of the following options:');
        $this->line('  --activate-all              Activate all inactive tools');
        $this->line('  --move-tools=source:target  Move tools between categories');
        $this->line('  --merge-categories=src:tgt  Merge categories (deletes source)');
        $this->newLine();
        $this->info('Example: php artisan fix:category-tools --move-tools=design:design-ui-ux');

        return self::SUCCESS;
    }

    protected function activateAllTools(): int
    {
        $inactiveTools = Tool::where('is_active', false)->get();

        if ($inactiveTools->isEmpty()) {
            $this->info('✓ All tools are already active!');

            return self::SUCCESS;
        }

        $this->warn("Found {$inactiveTools->count()} inactive tools:");
        foreach ($inactiveTools as $tool) {
            $this->line("  - {$tool->name} (slug: {$tool->slug})");
        }
        $this->newLine();

        if (! $this->confirm('Activate all these tools?', true)) {
            $this->info('Cancelled.');

            return self::SUCCESS;
        }

        Tool::where('is_active', false)->update(['is_active' => true]);

        $this->info("✓ Activated {$inactiveTools->count()} tools");

        return self::SUCCESS;
    }

    protected function moveTools(string $option): int
    {
        $parts = explode(':', $option);

        if (count($parts) !== 2) {
            $this->error('Invalid format. Use: source-slug:target-slug');

            return self::FAILURE;
        }

        [$sourceSlug, $targetSlug] = $parts;

        $sourceCategory = Category::where('slug', $sourceSlug)->first();
        $targetCategory = Category::where('slug', $targetSlug)->first();

        if (! $sourceCategory) {
            $this->error("Source category '{$sourceSlug}' not found");

            return self::FAILURE;
        }

        if (! $targetCategory) {
            $this->error("Target category '{$targetSlug}' not found");

            return self::FAILURE;
        }

        $tools = Tool::where('category_id', $sourceCategory->id)->get();

        if ($tools->isEmpty()) {
            $this->warn("No tools found in source category '{$sourceCategory->name}'");

            return self::SUCCESS;
        }

        $this->info("Found {$tools->count()} tools in '{$sourceCategory->name}':");
        foreach ($tools as $tool) {
            $this->line("  - {$tool->name}");
        }
        $this->newLine();

        if (! $this->confirm("Move these tools to '{$targetCategory->name}'?", true)) {
            $this->info('Cancelled.');

            return self::SUCCESS;
        }

        Tool::where('category_id', $sourceCategory->id)
            ->update(['category_id' => $targetCategory->id]);

        $this->info("✓ Moved {$tools->count()} tools from '{$sourceCategory->name}' to '{$targetCategory->name}'");

        return self::SUCCESS;
    }

    protected function mergeCategories(string $option): int
    {
        $parts = explode(':', $option);

        if (count($parts) !== 2) {
            $this->error('Invalid format. Use: source-slug:target-slug');

            return self::FAILURE;
        }

        [$sourceSlug, $targetSlug] = $parts;

        $sourceCategory = Category::where('slug', $sourceSlug)->first();
        $targetCategory = Category::where('slug', $targetSlug)->first();

        if (! $sourceCategory) {
            $this->error("Source category '{$sourceSlug}' not found");

            return self::FAILURE;
        }

        if (! $targetCategory) {
            $this->error("Target category '{$targetSlug}' not found");

            return self::FAILURE;
        }

        $tools = Tool::where('category_id', $sourceCategory->id)->get();

        $this->warn("⚠️  This will:");
        $this->line("  1. Move {$tools->count()} tools from '{$sourceCategory->name}' to '{$targetCategory->name}'");
        $this->line("  2. DELETE the '{$sourceCategory->name}' category");
        $this->newLine();

        if (! $this->confirm('Are you sure you want to continue?', false)) {
            $this->info('Cancelled.');

            return self::SUCCESS;
        }

        // Move tools
        Tool::where('category_id', $sourceCategory->id)
            ->update(['category_id' => $targetCategory->id]);

        // Delete source category
        $sourceCategory->delete();

        $this->info("✓ Merged '{$sourceCategory->name}' into '{$targetCategory->name}'");
        $this->info("✓ Moved {$tools->count()} tools");
        $this->info("✓ Deleted source category");

        return self::SUCCESS;
    }
}
