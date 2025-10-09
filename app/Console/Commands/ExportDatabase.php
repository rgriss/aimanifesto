<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Tool;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ExportDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:export {--file=} {--format=json}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export categories and tools to a JSON file';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting database export...');

        // Get all categories with their tools
        $categories = Category::with('tools')->get();

        // Prepare export data
        $exportData = [
            'exported_at' => now()->toIso8601String(),
            'version' => '1.0',
            'categories' => $categories->map(function ($category) {
                return [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'icon' => $category->icon,
                    'color' => $category->color,
                    'order' => $category->order,
                    'is_active' => $category->is_active,
                    'tools' => $category->tools->map(function ($tool) {
                        return [
                            'name' => $tool->name,
                            'slug' => $tool->slug,
                            'description' => $tool->description,
                            'url' => $tool->url,
                            'logo_url' => $tool->logo_url,
                            'pricing_model' => $tool->pricing_model,
                            'features' => $tool->features,
                            'use_cases' => $tool->use_cases,
                            'integrations' => $tool->integrations,
                            'ryan_rating' => $tool->ryan_rating,
                            'ryan_notes' => $tool->ryan_notes,
                            'is_featured' => $tool->is_featured,
                            'is_active' => $tool->is_active,
                        ];
                    })->values(),
                ];
            })->values(),
        ];

        // Determine filename
        $filename = $this->option('file') ?? 'export-'.now()->format('Y-m-d-His').'.json';

        // Ensure .json extension
        if (! str_ends_with($filename, '.json')) {
            $filename .= '.json';
        }

        // Export to storage/app/exports
        $path = 'exports/'.$filename;
        Storage::put($path, json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $fullPath = Storage::path($path);

        $this->info('Export completed successfully!');
        $this->line("File saved to: {$fullPath}");
        $this->line('Total categories: '.count($exportData['categories']));
        $this->line('Total tools: '.$categories->sum(fn ($c) => $c->tools->count()));

        return Command::SUCCESS;
    }
}
