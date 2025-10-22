<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Tool;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ContentExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:export
                            {--output= : Custom output file path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export categories and tools to JSON snapshot';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Exporting content...');
        $this->newLine();

        $startTime = microtime(true);

        try {
            // Fetch all categories and tools
            $categories = Category::orderBy('sort_order')->orderBy('name')->get();
            $tools = Tool::with('category')->orderBy('name')->get();

            // Build export data structure
            $exportData = [
                'metadata' => [
                    'version' => '1.0',
                    'exported_at' => now()->toIso8601String(),
                    'schema_version' => '1.0',
                    'exported_by' => 'php artisan content:export',
                    'record_counts' => [
                        'categories' => $categories->count(),
                        'tools' => $tools->count(),
                    ],
                ],
                'categories' => $categories->map(function ($category) {
                    return [
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'description' => $category->description,
                        'icon' => $category->icon,
                        'sort_order' => $category->sort_order,
                        'is_active' => $category->is_active,
                    ];
                })->toArray(),
                'tools' => $tools->map(function ($tool) {
                    return [
                        'name' => $tool->name,
                        'slug' => $tool->slug,
                        'category_slug' => $tool->category?->slug,
                        'description' => $tool->description,
                        'long_description' => $tool->long_description,
                        'website_url' => $tool->website_url,
                        'documentation_url' => $tool->documentation_url,
                        'logo_url' => $tool->logo_url,
                        'pricing_model' => $tool->pricing_model,
                        'price_description' => $tool->price_description,
                        'ryan_rating' => $tool->ryan_rating,
                        'ryan_notes' => $tool->ryan_notes,
                        'ryan_last_used' => $tool->ryan_last_used?->toDateString(),
                        'features' => $tool->features,
                        'use_cases' => $tool->use_cases,
                        'integrations' => $tool->integrations,
                        'is_featured' => $tool->is_featured,
                        'is_active' => $tool->is_active,
                        'first_reviewed_at' => $tool->first_reviewed_at?->toDateString(),
                    ];
                })->toArray(),
            ];

            // Determine output file path
            $outputPath = $this->option('output')
                ?? database_path('content/snapshots/snapshot-'.now()->format('Y-m-d').'.json');

            // Ensure directory exists
            File::ensureDirectoryExists(dirname($outputPath));

            // Write JSON file
            File::put($outputPath, json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            // Update latest.json
            $latestPath = database_path('content/latest.json');
            File::put($latestPath, json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            $duration = round(microtime(true) - $startTime, 2);

            // Display success message
            $this->components->twoColumnDetail('Exported categories', $categories->count());
            $this->components->twoColumnDetail('Exported tools', $tools->count());
            $this->components->twoColumnDetail('Saved to', $outputPath);
            $this->components->twoColumnDetail('Updated', $latestPath);
            $this->newLine();
            $this->info("Export completed in {$duration} seconds");

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Export failed: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
