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

    $latestFile = database_path('content/latest.json');
    if (File::exists($latestFile)) {
        File::delete($latestFile);
    }
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

test('export command creates snapshot file with correct structure', function () {
    // Create test data
    $category = Category::factory()->create([
        'name' => 'Test Category',
        'slug' => 'test-category',
    ]);

    $tool = Tool::factory()->create([
        'category_id' => $category->id,
        'name' => 'Test Tool',
        'slug' => 'test-tool',
    ]);

    // Run export command
    $this->artisan('content:export')
        ->assertSuccessful();

    // Check snapshot file exists
    $snapshotFile = database_path('content/snapshots/snapshot-'.now()->format('Y-m-d').'.json');
    expect(File::exists($snapshotFile))->toBeTrue();

    // Parse and validate JSON structure
    $data = json_decode(File::get($snapshotFile), true);

    expect($data)->toHaveKeys(['metadata', 'categories', 'tools']);
    expect($data['metadata'])->toHaveKeys(['version', 'exported_at', 'schema_version', 'record_counts']);
    expect($data['metadata']['schema_version'])->toBe('1.0');
    expect($data['metadata']['record_counts'])->toEqual([
        'categories' => 1,
        'tools' => 1,
    ]);
});

test('export command includes all category fields', function () {
    $category = Category::factory()->create([
        'name' => 'Code Assistants',
        'slug' => 'code-assistants',
        'description' => 'AI coding tools',
        'icon' => '💻',
        'sort_order' => 5,
        'is_active' => true,
    ]);

    $this->artisan('content:export')->assertSuccessful();

    $snapshotFile = database_path('content/snapshots/snapshot-'.now()->format('Y-m-d').'.json');
    $data = json_decode(File::get($snapshotFile), true);

    $exportedCategory = $data['categories'][0];
    expect($exportedCategory)->toEqual([
        'name' => 'Code Assistants',
        'slug' => 'code-assistants',
        'description' => 'AI coding tools',
        'icon' => '💻',
        'sort_order' => 5,
        'is_active' => true,
    ]);
});

test('export command includes all tool fields with category slug', function () {
    $category = Category::factory()->create(['slug' => 'test-category']);

    $tool = Tool::factory()->create([
        'category_id' => $category->id,
        'name' => 'GitHub Copilot',
        'slug' => 'github-copilot',
        'description' => 'AI pair programmer',
        'website_url' => 'https://github.com/features/copilot',
        'pricing_model' => 'paid',
        'ryan_rating' => 9,
        'features' => ['Auto-completion', 'Chat'],
        'use_cases' => ['Coding'],
        'integrations' => ['VS Code'],
        'is_featured' => true,
        'is_active' => true,
    ]);

    $this->artisan('content:export')->assertSuccessful();

    $snapshotFile = database_path('content/snapshots/snapshot-'.now()->format('Y-m-d').'.json');
    $data = json_decode(File::get($snapshotFile), true);

    $exportedTool = $data['tools'][0];
    expect($exportedTool['name'])->toBe('GitHub Copilot');
    expect($exportedTool['slug'])->toBe('github-copilot');
    expect($exportedTool['category_slug'])->toBe('test-category');
    expect($exportedTool['pricing_model'])->toBe('paid');
    expect($exportedTool['ryan_rating'])->toBe(9);
    expect($exportedTool['features'])->toBe(['Auto-completion', 'Chat']);
    expect($exportedTool['is_featured'])->toBeTrue();
});

test('export command updates latest.json file', function () {
    Category::factory()->create();

    $this->artisan('content:export')->assertSuccessful();

    $latestFile = database_path('content/latest.json');
    expect(File::exists($latestFile))->toBeTrue();

    $data = json_decode(File::get($latestFile), true);
    expect($data)->toHaveKeys(['metadata', 'categories', 'tools']);
});

test('export command handles empty database', function () {
    $this->artisan('content:export')->assertSuccessful();

    $snapshotFile = database_path('content/snapshots/snapshot-'.now()->format('Y-m-d').'.json');
    $data = json_decode(File::get($snapshotFile), true);

    expect($data['metadata']['record_counts'])->toEqual([
        'categories' => 0,
        'tools' => 0,
    ]);
    expect($data['categories'])->toBe([]);
    expect($data['tools'])->toBe([]);
});

test('export command can use custom output path', function () {
    Category::factory()->create();

    $customPath = storage_path('test-export.json');

    $this->artisan('content:export', ['--output' => $customPath])
        ->assertSuccessful();

    expect(File::exists($customPath))->toBeTrue();

    // Clean up
    File::delete($customPath);
});

test('export command preserves category sort order', function () {
    Category::factory()->create(['name' => 'Beta', 'slug' => 'beta', 'sort_order' => 2]);
    Category::factory()->create(['name' => 'Alpha', 'slug' => 'alpha', 'sort_order' => 1]);
    Category::factory()->create(['name' => 'Gamma', 'slug' => 'gamma', 'sort_order' => 3]);

    $this->artisan('content:export')->assertSuccessful();

    $snapshotFile = database_path('content/snapshots/snapshot-'.now()->format('Y-m-d').'.json');
    $data = json_decode(File::get($snapshotFile), true);

    // Should be ordered by sort_order, then name
    expect($data['categories'][0]['slug'])->toBe('alpha');
    expect($data['categories'][1]['slug'])->toBe('beta');
    expect($data['categories'][2]['slug'])->toBe('gamma');
});

test('export command orders tools alphabetically by name', function () {
    $category = Category::factory()->create();

    Tool::factory()->create(['category_id' => $category->id, 'name' => 'Zebra Tool', 'slug' => 'zebra-tool']);
    Tool::factory()->create(['category_id' => $category->id, 'name' => 'Alpha Tool', 'slug' => 'alpha-tool']);
    Tool::factory()->create(['category_id' => $category->id, 'name' => 'Bravo Tool', 'slug' => 'bravo-tool']);

    $this->artisan('content:export')->assertSuccessful();

    $snapshotFile = database_path('content/snapshots/snapshot-'.now()->format('Y-m-d').'.json');
    $data = json_decode(File::get($snapshotFile), true);

    // Should be ordered alphabetically by name
    expect($data['tools'][0]['name'])->toBe('Alpha Tool');
    expect($data['tools'][1]['name'])->toBe('Bravo Tool');
    expect($data['tools'][2]['name'])->toBe('Zebra Tool');
});
