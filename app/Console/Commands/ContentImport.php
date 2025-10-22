<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Tool;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ContentImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:import
                            {file? : Path to import file (defaults to latest.json)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import categories and tools from JSON snapshot';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $filePath = $this->argument('file') ?? database_path('content/latest.json');

        if (! File::exists($filePath)) {
            $this->error("Import file not found: {$filePath}");

            return self::FAILURE;
        }

        $this->info("Importing content from {$filePath}...");
        $this->newLine();

        $startTime = microtime(true);

        try {
            // Read and parse JSON
            $json = File::get($filePath);
            $data = json_decode($json, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON: '.json_last_error_msg());
            }

            // Validate snapshot structure
            $this->validateSnapshot($data);

            // Display metadata
            $this->line('Validating snapshot...');
            $this->components->twoColumnDetail('Schema version', $data['metadata']['schema_version'].' (compatible)');
            $this->components->twoColumnDetail('Exported at', $data['metadata']['exported_at']);
            $this->newLine();

            // Import within transaction
            DB::transaction(function () use ($data) {
                $this->importCategories($data['categories']);
                $this->importTools($data['tools']);
            });

            $duration = round(microtime(true) - $startTime, 2);

            $this->newLine();
            $this->info("Import completed in {$duration} seconds");

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Import failed: '.$e->getMessage());

            return self::FAILURE;
        }
    }

    /**
     * Validate the snapshot structure
     */
    protected function validateSnapshot(array $data): void
    {
        if (! isset($data['metadata']['schema_version'])) {
            throw new \Exception('Missing schema version in snapshot');
        }

        if ($data['metadata']['schema_version'] !== '1.0') {
            throw new \Exception('Incompatible schema version: '.$data['metadata']['schema_version']);
        }

        if (! isset($data['categories']) || ! isset($data['tools'])) {
            throw new \Exception('Missing categories or tools data in snapshot');
        }
    }

    /**
     * Import categories with upsert logic
     */
    protected function importCategories(array $categories): void
    {
        $this->line('Processing categories...');

        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($categories as $categoryData) {
            try {
                $category = Category::where('slug', $categoryData['slug'])->first();

                if ($category) {
                    // Update existing
                    $category->update($categoryData);
                    $updated++;
                } else {
                    // Create new
                    Category::create($categoryData);
                    $created++;
                }
            } catch (\Exception $e) {
                $this->warn("Skipped category '{$categoryData['slug']}': ".$e->getMessage());
                $skipped++;
            }
        }

        $this->components->twoColumnDetail('Created', $created);
        $this->components->twoColumnDetail('Updated', $updated);
        $this->components->twoColumnDetail('Skipped', $skipped);
        $this->newLine();
    }

    /**
     * Import tools with upsert logic
     */
    protected function importTools(array $tools): void
    {
        $this->line('Processing tools...');

        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($tools as $toolData) {
            try {
                // Find category by slug
                $category = Category::where('slug', $toolData['category_slug'])->first();

                if (! $category) {
                    throw new \Exception("Category not found: {$toolData['category_slug']}");
                }

                // Replace category_slug with category_id
                $toolData['category_id'] = $category->id;
                unset($toolData['category_slug']);

                // Find existing tool
                $tool = Tool::where('slug', $toolData['slug'])->first();

                if ($tool) {
                    // Update existing (preserve views_count)
                    $viewsCount = $tool->views_count;
                    $tool->update($toolData);
                    $tool->update(['views_count' => $viewsCount]);
                    $updated++;
                } else {
                    // Create new
                    Tool::create($toolData);
                    $created++;
                }
            } catch (\Exception $e) {
                $this->warn("Skipped tool '{$toolData['slug']}': ".$e->getMessage());
                $skipped++;
            }
        }

        $this->components->twoColumnDetail('Created', $created);
        $this->components->twoColumnDetail('Updated', $updated);
        $this->components->twoColumnDetail('Skipped', $skipped);
    }
}
