<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Tool;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncProduction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:production
                            {--url= : Production API URL (default: from .env)}
                            {--token= : API token (default: from .env)}
                            {--fresh : Wipe local database before syncing}
                            {--categories-only : Only sync categories}
                            {--tools-only : Only sync tools}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync categories and tools from production API to local database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔄 Starting production sync...');
        $this->newLine();

        // Get API credentials
        $apiUrl = $this->option('url') ?: env('PRODUCTION_API_URL', 'https://aimanifesto.com');
        $apiToken = $this->option('token') ?: env('PRODUCTION_API_TOKEN');

        if (! $apiToken) {
            $this->error('❌ API token is required. Set PRODUCTION_API_TOKEN in .env or use --token option.');

            return self::FAILURE;
        }

        // Confirm if fresh sync
        if ($this->option('fresh')) {
            if (! $this->confirm('⚠️  This will delete all local categories and tools. Continue?', false)) {
                $this->info('Sync cancelled.');

                return self::SUCCESS;
            }

            $this->info('Wiping local database...');
            Tool::query()->delete();
            Category::query()->delete();
            $this->info('✓ Local database wiped');
            $this->newLine();
        }

        $syncCategories = ! $this->option('tools-only');
        $syncTools = ! $this->option('categories-only');

        // Sync Categories
        if ($syncCategories) {
            $this->info('📂 Syncing categories...');
            $categoriesResult = $this->syncCategories($apiUrl, $apiToken);

            if (! $categoriesResult) {
                return self::FAILURE;
            }

            $this->newLine();
        }

        // Sync Tools
        if ($syncTools) {
            $this->info('🔧 Syncing tools...');
            $toolsResult = $this->syncTools($apiUrl, $apiToken);

            if (! $toolsResult) {
                return self::FAILURE;
            }
        }

        $this->newLine();
        $this->info('✅ Production sync completed successfully!');
        $this->newLine();
        $this->info('Local database now matches production.');

        return self::SUCCESS;
    }

    /**
     * Sync categories from production.
     */
    protected function syncCategories(string $apiUrl, string $apiToken): bool
    {
        try {
            $response = Http::withToken($apiToken)
                ->timeout(30)
                ->get("{$apiUrl}/api/categories");

            if (! $response->successful()) {
                $this->error("❌ Failed to fetch categories: {$response->status()}");
                $this->error($response->body());

                return false;
            }

            $categories = $response->json('data');

            $bar = $this->output->createProgressBar(count($categories));
            $bar->start();

            foreach ($categories as $categoryData) {
                Category::updateOrCreate(
                    ['slug' => $categoryData['slug']],
                    [
                        'name' => $categoryData['name'],
                        'description' => $categoryData['description'],
                        'icon' => $categoryData['icon'],
                        'is_active' => $categoryData['is_active'],
                        'sort_order' => $categoryData['sort_order'],
                    ]
                );

                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            $this->info('✓ Synced '.count($categories).' categories');

            return true;

        } catch (\Exception $e) {
            $this->error("❌ Error syncing categories: {$e->getMessage()}");

            return false;
        }
    }

    /**
     * Sync tools from production.
     */
    protected function syncTools(string $apiUrl, string $apiToken): bool
    {
        try {
            // Fetch all tools (paginated)
            $allTools = [];
            $page = 1;
            $totalPages = 1;

            do {
                $response = Http::withToken($apiToken)
                    ->timeout(30)
                    ->get("{$apiUrl}/api/tools", [
                        'per_page' => 100,
                        'page' => $page,
                    ]);

                if (! $response->successful()) {
                    $this->error("❌ Failed to fetch tools (page {$page}): {$response->status()}");
                    $this->error($response->body());

                    return false;
                }

                $data = $response->json();
                $allTools = array_merge($allTools, $data['data']);

                if (isset($data['meta'])) {
                    $totalPages = $data['meta']['last_page'];
                    $this->info("Fetching page {$page} of {$totalPages}...");
                }

                $page++;
            } while ($page <= $totalPages);

            // Now sync all tools
            $this->info('Syncing '.count($allTools).' tools...');
            $bar = $this->output->createProgressBar(count($allTools));
            $bar->start();

            $synced = 0;
            $errors = 0;

            foreach ($allTools as $toolData) {
                try {
                    // Find category by slug
                    $category = Category::where('slug', $toolData['category']['slug'])->first();

                    if (! $category) {
                        $this->newLine();
                        $this->warn("⚠️  Category '{$toolData['category']['slug']}' not found for tool '{$toolData['name']}'. Skipping...");
                        $errors++;
                        $bar->advance();
                        continue;
                    }

                    // Fetch full tool details
                    $detailResponse = Http::withToken($apiToken)
                        ->timeout(30)
                        ->get("{$apiUrl}/api/tools/{$toolData['slug']}");

                    if (! $detailResponse->successful()) {
                        $this->newLine();
                        $this->warn("⚠️  Failed to fetch details for '{$toolData['name']}'");
                        $errors++;
                        $bar->advance();
                        continue;
                    }

                    $fullTool = $detailResponse->json('data');

                    Tool::updateOrCreate(
                        ['slug' => $fullTool['slug']],
                        [
                            'category_id' => $category->id,
                            'name' => $fullTool['name'],
                            'description' => $fullTool['description'],
                            'long_description' => $fullTool['long_description'] ?? null,
                            'website_url' => $fullTool['website_url'],
                            'documentation_url' => $fullTool['documentation_url'] ?? null,
                            'logo_url' => $fullTool['logo_url'] ?? null,
                            'pricing_model' => $fullTool['pricing_model'],
                            'price_description' => $fullTool['price_description'] ?? null,
                            'features' => $fullTool['features'] ?? [],
                            'use_cases' => $fullTool['use_cases'] ?? [],
                            'integrations' => $fullTool['integrations'] ?? [],
                            'ryan_rating' => $fullTool['ryan_rating'] ?? null,
                            'ryan_notes' => $fullTool['ryan_notes'] ?? null,
                            'ryan_last_used' => $fullTool['ryan_last_used'] ?? null,
                            'is_featured' => $fullTool['is_featured'] ?? false,
                            'is_active' => $fullTool['is_active'] ?? true,
                            'first_reviewed_at' => $fullTool['first_reviewed_at'] ?? null,
                            'views_count' => $fullTool['views_count'] ?? 0,
                        ]
                    );

                    $synced++;
                } catch (\Exception $e) {
                    $this->newLine();
                    $this->warn("⚠️  Error syncing tool '{$toolData['name']}': {$e->getMessage()}");
                    $errors++;
                }

                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            $this->info("✓ Synced {$synced} tools");

            if ($errors > 0) {
                $this->warn("⚠️  {$errors} tools had errors");
            }

            return true;

        } catch (\Exception $e) {
            $this->error("❌ Error syncing tools: {$e->getMessage()}");

            return false;
        }
    }
}
