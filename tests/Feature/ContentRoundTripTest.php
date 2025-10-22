<?php

use App\Models\Category;
use App\Models\Tool;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    // Clean up any existing export files
    $snapshotsDir = database_path('content/snapshots');
    if (File::exists($snapshotsDir)) {
        File::deleteDirectory($snapshotsDir);
    }
    File::ensureDirectoryExists($snapshotsDir);
});

afterEach(function () {
    // Clean up after tests
    $snapshotsDir = database_path('content/snapshots');
    if (File::exists($snapshotsDir)) {
        File::deleteDirectory($snapshotsDir);
    }

    $latestFile = database_path('content/latest.json');
    if (File::exists($latestFile)) {
        File::delete($latestFile);
    }
});

test('full round trip export and import preserves all data', function () {
    // Create test data
    $category1 = Category::factory()->create([
        'name' => 'Code Assistants',
        'slug' => 'code-assistants',
        'description' => 'AI coding tools',
        'icon' => '💻',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    $category2 = Category::factory()->create([
        'name' => 'Writing Tools',
        'slug' => 'writing-tools',
        'description' => 'AI writing assistants',
        'icon' => '✍️',
        'sort_order' => 2,
        'is_active' => true,
    ]);

    $tool1 = Tool::factory()->create([
        'category_id' => $category1->id,
        'name' => 'GitHub Copilot',
        'slug' => 'github-copilot',
        'description' => 'AI pair programmer',
        'website_url' => 'https://github.com/features/copilot',
        'pricing_model' => 'paid',
        'ryan_rating' => 9,
        'features' => ['Auto-completion', 'Chat', 'Code generation'],
        'use_cases' => ['Coding', 'Documentation', 'Learning'],
        'integrations' => ['VS Code', 'JetBrains', 'Neovim'],
        'is_featured' => true,
        'is_active' => true,
        'views_count' => 100, // Should be preserved on import
    ]);

    $tool2 = Tool::factory()->create([
        'category_id' => $category2->id,
        'name' => 'Grammarly',
        'slug' => 'grammarly',
        'description' => 'AI writing assistant',
        'website_url' => 'https://grammarly.com',
        'pricing_model' => 'freemium',
        'ryan_rating' => 7,
        'features' => ['Grammar checking', 'Style suggestions'],
        'use_cases' => ['Writing', 'Editing'],
        'integrations' => ['Chrome', 'Word'],
        'is_featured' => false,
        'is_active' => true,
        'views_count' => 50,
    ]);

    // Store original data
    $originalCategories = Category::all()->sortBy('slug')->values();
    $originalTools = Tool::all()->sortBy('slug')->values();

    // Export
    $this->artisan('content:export')->assertSuccessful();

    // Delete all data
    Tool::query()->delete();
    Category::query()->delete();

    expect(Category::count())->toBe(0);
    expect(Tool::count())->toBe(0);

    // Import from latest.json
    $this->artisan('content:import')->assertSuccessful();

    // Verify data was restored
    expect(Category::count())->toBe(2);
    expect(Tool::count())->toBe(2);

    // Verify categories
    $importedCategories = Category::all()->sortBy('slug')->values();
    expect($importedCategories[0]->slug)->toBe($originalCategories[0]->slug);
    expect($importedCategories[0]->name)->toBe($originalCategories[0]->name);
    expect($importedCategories[0]->description)->toBe($originalCategories[0]->description);
    expect($importedCategories[0]->icon)->toBe($originalCategories[0]->icon);
    expect($importedCategories[0]->sort_order)->toBe($originalCategories[0]->sort_order);

    expect($importedCategories[1]->slug)->toBe($originalCategories[1]->slug);
    expect($importedCategories[1]->name)->toBe($originalCategories[1]->name);

    // Verify tools
    $importedTools = Tool::all()->sortBy('slug')->values();

    expect($importedTools[0]->slug)->toBe('github-copilot');
    expect($importedTools[0]->name)->toBe('GitHub Copilot');
    expect($importedTools[0]->ryan_rating)->toBe(9);
    expect($importedTools[0]->features)->toBe(['Auto-completion', 'Chat', 'Code generation']);
    expect($importedTools[0]->use_cases)->toBe(['Coding', 'Documentation', 'Learning']);
    expect($importedTools[0]->integrations)->toBe(['VS Code', 'JetBrains', 'Neovim']);
    expect($importedTools[0]->is_featured)->toBeTrue();
    expect($importedTools[0]->category->slug)->toBe('code-assistants');

    expect($importedTools[1]->slug)->toBe('grammarly');
    expect($importedTools[1]->name)->toBe('Grammarly');
    expect($importedTools[1]->ryan_rating)->toBe(7);
    expect($importedTools[1]->is_featured)->toBeFalse();
    expect($importedTools[1]->category->slug)->toBe('writing-tools');
});

test('round trip with updates preserves new data on import', function () {
    // Create initial data
    $category = Category::factory()->create([
        'slug' => 'code-assistants',
        'name' => 'Old Name',
        'description' => 'Old description',
    ]);

    $tool = Tool::factory()->create([
        'category_id' => $category->id,
        'slug' => 'test-tool',
        'name' => 'Old Tool Name',
        'ryan_rating' => 5,
        'views_count' => 100,
    ]);

    // Export
    $this->artisan('content:export')->assertSuccessful();

    // Modify data in database
    $category->update([
        'name' => 'Updated Name',
        'description' => 'Updated description',
    ]);

    $tool->update([
        'name' => 'Updated Tool Name',
        'ryan_rating' => 8,
        'views_count' => 200, // This should be preserved despite import
    ]);

    // Import from snapshot (should revert to exported values except views_count)
    $this->artisan('content:import')->assertSuccessful();

    $category->refresh();
    expect($category->name)->toBe('Old Name');
    expect($category->description)->toBe('Old description');

    $tool->refresh();
    expect($tool->name)->toBe('Old Tool Name');
    expect($tool->ryan_rating)->toBe(5);
    expect($tool->views_count)->toBe(200); // Should be preserved
});

test('round trip handles complex JSON fields correctly', function () {
    $category = Category::factory()->create(['slug' => 'test']);

    $tool = Tool::factory()->create([
        'category_id' => $category->id,
        'slug' => 'complex-tool',
        'features' => [
            'Feature 1 with special chars: <>&"\'',
            'Feature 2 with unicode: 🚀🎉',
            'Feature 3 with quotes: "quoted text"',
        ],
        'use_cases' => [
            'Use case with newline\ntext',
            'Use case with tabs\ttab',
        ],
        'integrations' => null, // Test null array
    ]);

    // Export and import
    $this->artisan('content:export')->assertSuccessful();

    Tool::query()->delete();
    Category::query()->delete();

    $this->artisan('content:import')->assertSuccessful();

    $importedTool = Tool::where('slug', 'complex-tool')->first();
    expect($importedTool->features)->toBe([
        'Feature 1 with special chars: <>&"\'',
        'Feature 2 with unicode: 🚀🎉',
        'Feature 3 with quotes: "quoted text"',
    ]);
    expect($importedTool->use_cases)->toBe([
        'Use case with newline\ntext',
        'Use case with tabs\ttab',
    ]);
    expect($importedTool->integrations)->toBeNull();
});

test('round trip preserves inactive records', function () {
    $inactiveCategory = Category::factory()->create([
        'slug' => 'inactive-category',
        'is_active' => false,
    ]);

    $inactiveTool = Tool::factory()->create([
        'category_id' => $inactiveCategory->id,
        'slug' => 'inactive-tool',
        'is_active' => false,
    ]);

    // Export and import
    $this->artisan('content:export')->assertSuccessful();

    Tool::query()->delete();
    Category::query()->delete();

    $this->artisan('content:import')->assertSuccessful();

    $importedCategory = Category::where('slug', 'inactive-category')->first();
    expect($importedCategory->is_active)->toBeFalse();

    $importedTool = Tool::where('slug', 'inactive-tool')->first();
    expect($importedTool->is_active)->toBeFalse();
});

test('multiple exports create separate snapshot files', function () {
    Category::factory()->create();

    // First export
    $this->artisan('content:export')->assertSuccessful();
    $firstSnapshot = database_path('content/snapshots/snapshot-'.now()->format('Y-m-d').'.json');
    expect(File::exists($firstSnapshot))->toBeTrue();

    // Export to custom file (simulating different day)
    $secondSnapshot = database_path('content/snapshots/snapshot-2025-10-23.json');
    $this->artisan('content:export', ['--output' => $secondSnapshot])->assertSuccessful();

    // Both files should exist
    expect(File::exists($firstSnapshot))->toBeTrue();
    expect(File::exists($secondSnapshot))->toBeTrue();

    // Clean up second snapshot
    File::delete($secondSnapshot);
});

test('import from specific snapshot file works correctly', function () {
    $category = Category::factory()->create(['slug' => 'test-category']);

    // Export to custom snapshot
    $snapshotFile = database_path('content/snapshots/custom-snapshot.json');
    $this->artisan('content:export', ['--output' => $snapshotFile])->assertSuccessful();

    // Clear database
    Category::query()->delete();
    expect(Category::count())->toBe(0);

    // Import from specific file
    $this->artisan('content:import', ['file' => $snapshotFile])->assertSuccessful();

    expect(Category::count())->toBe(1);
    expect(Category::where('slug', 'test-category')->exists())->toBeTrue();

    // Clean up
    File::delete($snapshotFile);
});
