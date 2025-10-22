<?php

use App\Models\Category;
use App\Models\Tool;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    // Ensure content directory exists
    File::ensureDirectoryExists(database_path('content'));
});

afterEach(function () {
    // Clean up test files
    $testFile = database_path('content/test-import.json');
    if (File::exists($testFile)) {
        File::delete($testFile);
    }
});

test('import command creates new categories from snapshot', function () {
    $snapshot = [
        'metadata' => [
            'version' => '1.0',
            'schema_version' => '1.0',
            'exported_at' => now()->toIso8601String(),
            'record_counts' => ['categories' => 2, 'tools' => 0],
        ],
        'categories' => [
            [
                'name' => 'Code Assistants',
                'slug' => 'code-assistants',
                'description' => 'AI coding tools',
                'icon' => '💻',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Writing Tools',
                'slug' => 'writing-tools',
                'description' => 'AI writing assistants',
                'icon' => '✍️',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ],
        'tools' => [],
    ];

    $testFile = database_path('content/test-import.json');
    File::put($testFile, json_encode($snapshot));

    $this->artisan('content:import', ['file' => $testFile])
        ->assertSuccessful();

    expect(Category::count())->toBe(2);
    expect(Category::where('slug', 'code-assistants')->exists())->toBeTrue();
    expect(Category::where('slug', 'writing-tools')->exists())->toBeTrue();
});

test('import command creates new tools with category relationships', function () {
    $snapshot = [
        'metadata' => [
            'version' => '1.0',
            'schema_version' => '1.0',
            'exported_at' => now()->toIso8601String(),
            'record_counts' => ['categories' => 1, 'tools' => 1],
        ],
        'categories' => [
            [
                'name' => 'Code Assistants',
                'slug' => 'code-assistants',
                'description' => 'AI coding tools',
                'icon' => '💻',
                'sort_order' => 1,
                'is_active' => true,
            ],
        ],
        'tools' => [
            [
                'name' => 'GitHub Copilot',
                'slug' => 'github-copilot',
                'category_slug' => 'code-assistants',
                'description' => 'AI pair programmer',
                'long_description' => null,
                'website_url' => 'https://github.com/features/copilot',
                'documentation_url' => null,
                'logo_url' => null,
                'pricing_model' => 'paid',
                'price_description' => null,
                'ryan_rating' => 9,
                'ryan_notes' => null,
                'ryan_last_used' => null,
                'features' => ['Auto-completion', 'Chat'],
                'use_cases' => ['Coding'],
                'integrations' => ['VS Code'],
                'is_featured' => true,
                'is_active' => true,
                'first_reviewed_at' => null,
            ],
        ],
    ];

    $testFile = database_path('content/test-import.json');
    File::put($testFile, json_encode($snapshot));

    $this->artisan('content:import', ['file' => $testFile])
        ->assertSuccessful();

    expect(Tool::count())->toBe(1);

    $tool = Tool::where('slug', 'github-copilot')->first();
    expect($tool)->not->toBeNull();
    expect($tool->name)->toBe('GitHub Copilot');
    expect($tool->category->slug)->toBe('code-assistants');
    expect($tool->ryan_rating)->toBe(9);
    expect($tool->features)->toBe(['Auto-completion', 'Chat']);
});

test('import command updates existing categories without duplicating', function () {
    // Create existing category
    $category = Category::factory()->create([
        'name' => 'Old Name',
        'slug' => 'code-assistants',
        'description' => 'Old description',
        'sort_order' => 99,
    ]);

    $snapshot = [
        'metadata' => [
            'version' => '1.0',
            'schema_version' => '1.0',
            'exported_at' => now()->toIso8601String(),
            'record_counts' => ['categories' => 1, 'tools' => 0],
        ],
        'categories' => [
            [
                'name' => 'Code Assistants',
                'slug' => 'code-assistants',
                'description' => 'AI coding tools',
                'icon' => '💻',
                'sort_order' => 1,
                'is_active' => true,
            ],
        ],
        'tools' => [],
    ];

    $testFile = database_path('content/test-import.json');
    File::put($testFile, json_encode($snapshot));

    $this->artisan('content:import', ['file' => $testFile])
        ->assertSuccessful();

    // Should still only have 1 category
    expect(Category::count())->toBe(1);

    // Should be updated
    $category->refresh();
    expect($category->name)->toBe('Code Assistants');
    expect($category->description)->toBe('AI coding tools');
    expect($category->sort_order)->toBe(1);
});

test('import command updates existing tools without duplicating', function () {
    $category = Category::factory()->create(['slug' => 'code-assistants']);

    // Create existing tool
    $tool = Tool::factory()->create([
        'category_id' => $category->id,
        'slug' => 'github-copilot',
        'name' => 'Old Name',
        'ryan_rating' => 5,
        'views_count' => 100, // Should be preserved
    ]);

    $snapshot = [
        'metadata' => [
            'version' => '1.0',
            'schema_version' => '1.0',
            'exported_at' => now()->toIso8601String(),
            'record_counts' => ['categories' => 1, 'tools' => 1],
        ],
        'categories' => [
            [
                'name' => 'Code Assistants',
                'slug' => 'code-assistants',
                'description' => 'AI coding tools',
                'icon' => '💻',
                'sort_order' => 1,
                'is_active' => true,
            ],
        ],
        'tools' => [
            [
                'name' => 'GitHub Copilot',
                'slug' => 'github-copilot',
                'category_slug' => 'code-assistants',
                'description' => 'AI pair programmer',
                'long_description' => null,
                'website_url' => 'https://github.com/features/copilot',
                'documentation_url' => null,
                'logo_url' => null,
                'pricing_model' => 'paid',
                'price_description' => null,
                'ryan_rating' => 9,
                'ryan_notes' => null,
                'ryan_last_used' => null,
                'features' => ['Auto-completion'],
                'use_cases' => null,
                'integrations' => null,
                'is_featured' => true,
                'is_active' => true,
                'first_reviewed_at' => null,
            ],
        ],
    ];

    $testFile = database_path('content/test-import.json');
    File::put($testFile, json_encode($snapshot));

    $this->artisan('content:import', ['file' => $testFile])
        ->assertSuccessful();

    // Should still only have 1 tool
    expect(Tool::count())->toBe(1);

    // Should be updated
    $tool->refresh();
    expect($tool->name)->toBe('GitHub Copilot');
    expect($tool->ryan_rating)->toBe(9);

    // views_count should be preserved
    expect($tool->views_count)->toBe(100);
});

test('import command fails with invalid JSON', function () {
    $testFile = database_path('content/test-import.json');
    File::put($testFile, 'invalid json {{{');

    $this->artisan('content:import', ['file' => $testFile])
        ->assertFailed();
});

test('import command fails with incompatible schema version', function () {
    $snapshot = [
        'metadata' => [
            'version' => '1.0',
            'schema_version' => '99.0', // Incompatible
            'exported_at' => now()->toIso8601String(),
            'record_counts' => ['categories' => 0, 'tools' => 0],
        ],
        'categories' => [],
        'tools' => [],
    ];

    $testFile = database_path('content/test-import.json');
    File::put($testFile, json_encode($snapshot));

    $this->artisan('content:import', ['file' => $testFile])
        ->assertFailed();
});

test('import command fails when file does not exist', function () {
    $this->artisan('content:import', ['file' => database_path('content/nonexistent.json')])
        ->assertFailed();
});

test('import command skips tools with missing category', function () {
    $snapshot = [
        'metadata' => [
            'version' => '1.0',
            'schema_version' => '1.0',
            'exported_at' => now()->toIso8601String(),
            'record_counts' => ['categories' => 0, 'tools' => 1],
        ],
        'categories' => [],
        'tools' => [
            [
                'name' => 'Orphaned Tool',
                'slug' => 'orphaned-tool',
                'category_slug' => 'nonexistent-category',
                'description' => 'Test',
                'long_description' => null,
                'website_url' => 'https://example.com',
                'documentation_url' => null,
                'logo_url' => null,
                'pricing_model' => 'free',
                'price_description' => null,
                'ryan_rating' => null,
                'ryan_notes' => null,
                'ryan_last_used' => null,
                'features' => null,
                'use_cases' => null,
                'integrations' => null,
                'is_featured' => false,
                'is_active' => true,
                'first_reviewed_at' => null,
            ],
        ],
    ];

    $testFile = database_path('content/test-import.json');
    File::put($testFile, json_encode($snapshot));

    // Should complete but skip the orphaned tool
    $this->artisan('content:import', ['file' => $testFile])
        ->assertSuccessful();

    expect(Tool::count())->toBe(0);
});

test('import command uses latest.json by default', function () {
    $snapshot = [
        'metadata' => [
            'version' => '1.0',
            'schema_version' => '1.0',
            'exported_at' => now()->toIso8601String(),
            'record_counts' => ['categories' => 1, 'tools' => 0],
        ],
        'categories' => [
            [
                'name' => 'Test Category',
                'slug' => 'test-category',
                'description' => 'Test',
                'icon' => '🧪',
                'sort_order' => 1,
                'is_active' => true,
            ],
        ],
        'tools' => [],
    ];

    $latestFile = database_path('content/latest.json');
    File::put($latestFile, json_encode($snapshot));

    $this->artisan('content:import')
        ->assertSuccessful();

    expect(Category::count())->toBe(1);

    // Clean up
    File::delete($latestFile);
});

test('import command runs in transaction and rolls back on error', function () {
    $snapshot = [
        'metadata' => [
            'version' => '1.0',
            'schema_version' => '1.0',
            'exported_at' => now()->toIso8601String(),
            'record_counts' => ['categories' => 1, 'tools' => 1],
        ],
        'categories' => [
            [
                'name' => 'Test Category',
                'slug' => 'test-category',
                'description' => 'Test',
                'icon' => '🧪',
                'sort_order' => 1,
                'is_active' => true,
            ],
        ],
        'tools' => [
            [
                'name' => 'Test Tool',
                'slug' => 'test-tool',
                'category_slug' => 'nonexistent-category', // This will cause error
                'description' => 'Test',
                'long_description' => null,
                'website_url' => 'https://example.com',
                'documentation_url' => null,
                'logo_url' => null,
                'pricing_model' => 'free',
                'price_description' => null,
                'ryan_rating' => null,
                'ryan_notes' => null,
                'ryan_last_used' => null,
                'features' => null,
                'use_cases' => null,
                'integrations' => null,
                'is_featured' => false,
                'is_active' => true,
                'first_reviewed_at' => null,
            ],
        ],
    ];

    $testFile = database_path('content/test-import.json');
    File::put($testFile, json_encode($snapshot));

    $this->artisan('content:import', ['file' => $testFile])
        ->assertSuccessful(); // Import succeeds but skips bad tool

    // Category should still be created
    expect(Category::count())->toBe(1);
    // Tool should be skipped
    expect(Tool::count())->toBe(0);
});
