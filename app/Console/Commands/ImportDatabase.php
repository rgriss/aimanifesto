<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Tool;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImportDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:import {file} {--merge : Merge with existing data instead of replacing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import categories and tools from a JSON file';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $filename = $this->argument('file');
        $merge = $this->option('merge');

        $this->info('Starting database import...');

        // Check if file exists
        $path = 'exports/'.$filename;
        if (! Storage::exists($path)) {
            $this->error("File not found: {$path}");

            return Command::FAILURE;
        }

        // Read and decode JSON
        $content = Storage::get($path);
        $data = json_decode($content, true);

        if (! $data) {
            $this->error('Invalid JSON file');

            return Command::FAILURE;
        }

        // Validate data structure
        if (! isset($data['categories']) || ! is_array($data['categories'])) {
            $this->error('Invalid data structure: missing categories array');

            return Command::FAILURE;
        }

        DB::beginTransaction();

        try {
            if (! $merge) {
                // Clear existing data
                if ($this->confirm('This will delete all existing categories and tools. Continue?', false)) {
                    $this->info('Clearing existing data...');
                    Tool::query()->delete();
                    Category::query()->delete();
                } else {
                    $this->info('Import cancelled.');

                    return Command::SUCCESS;
                }
            }

            $categoriesCreated = 0;
            $categoriesUpdated = 0;
            $toolsCreated = 0;
            $toolsUpdated = 0;

            foreach ($data['categories'] as $categoryData) {
                // Extract tools before creating/updating category
                $tools = $categoryData['tools'] ?? [];
                unset($categoryData['tools']);

                // Find or create category
                $category = Category::where('slug', $categoryData['slug'])->first();

                if ($category) {
                    $category->update($categoryData);
                    $categoriesUpdated++;
                    $this->line("Updated category: {$category->name}");
                } else {
                    $category = Category::create($categoryData);
                    $categoriesCreated++;
                    $this->line("Created category: {$category->name}");
                }

                // Import tools for this category
                foreach ($tools as $toolData) {
                    $toolData['category_id'] = $category->id;

                    $tool = Tool::where('slug', $toolData['slug'])->first();

                    if ($tool) {
                        $tool->update($toolData);
                        $toolsUpdated++;
                        $this->line("  Updated tool: {$tool->name}");
                    } else {
                        $tool = Tool::create($toolData);
                        $toolsCreated++;
                        $this->line("  Created tool: {$tool->name}");
                    }
                }
            }

            DB::commit();

            $this->info('Import completed successfully!');
            $this->line("Categories created: {$categoriesCreated}");
            $this->line("Categories updated: {$categoriesUpdated}");
            $this->line("Tools created: {$toolsCreated}");
            $this->line("Tools updated: {$toolsUpdated}");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Import failed: '.$e->getMessage());

            return Command::FAILURE;
        }
    }
}
